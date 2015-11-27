<?php
namespace hellaEngine\Supports\Common\Util;
/**
 * 验证码
 *
 * @package util
 * @author changbin
 */
class CommonUtilCaptcha
{

	private $forecolor = array(75, 26, 32);  //字体颜色
	private $backcolor = array(250, 244, 234); //背景颜色
	private $width = 92;   //图片宽度
	private $height = 32;  //图片高度
	private $fonts;   //字体列表
	private $memcache;
	private $prefix = '/kxm/captcha/';
	private $prefixLevel = 'captcahLevel';

	const CAPTCHA_NORMAL = 0;  // 不需要验证码
	const CAPTCHA_COMMON = 1;  // 普通验证码
	const CAPTCHA_ADVANCE = 2;  // 高级验证码
	const CAPTCHA_EMPTY = 3;  // 验证码为空
	const CAPTCHA_ERROR = 4;  // 验证码错误

	private $mcPrefix;
	private $memCache;

	function __construct($mcPrefix)
	{
//        $this->memcache = new Mid_MCache();
//	    $this->fonts = array(
//            'CALIBRI'  => array('minSize' => 21, 'maxSize' => 23, 'font' => 'CALIBRI.TTF'),
//            'georgiai' => array( 'minSize' =>19, 'maxSize' => 22, 'font' => 'georgiai.ttf'),
//            'ANTQUAI'  => array( 'minSize' => 20, 'maxSize' => 23, 'font' => 'ANTQUAI.TTF'),
//            'BKANT'    => array( 'minSize' => 18, 'maxSize' => 21, 'font' => 'BKANT.TTF'),
//            'times'    => array('minSize' => 21, 'maxSize' => 23, 'font' => 'times.ttf'));

		$this->mcPrefix = $mcPrefix;
		$this->memCache = new Common_DB_MemCache(MEMCACHE_SERVICE_KXI, POOL_MEMCACHE_GROUPNAME);
	}

	/**
	 * 检查验证码
	 * key : $prefix . $rcode,  value : $captcha
	 */
	public function checkCaptcha($captcha, $rcode, $action = "", $del=false)
	{
		$captcha = strtolower($captcha);

		$level = self::CAPTCHA_COMMON;
		if (strlen($action))
		{
			$level = Tool_Limit::getCaptchaLevel($action);
			if ($level != self::CAPTCHA_ADVANCE)
			{
				$level = self::CAPTCHA_COMMON;
			}
		}
		$captchaLevel = $this->memcache->get($this->prefixLevel . $rcode);
		if (empty($captchaLevel))
		{
			$captchaLevel = self::CAPTCHA_NORMAL;
		}
		if ($level != $captchaLevel)
		{
			return false;
		}
		$res = $this->memcache->get($this->prefix . $rcode);
		if ($res === false || $res != $captcha)
		{
			return false;
		}
		if ($del)
		{
			$this->memcache->delete($this->prefix . $rcode);
		}
		return true;
	}

	public function checkCaptchaException($captcha, $rcode, $level = self::CAPTCHA_NORMAL, $del = false)
	{
		if (strlen($captcha))
		{
			if (!$this->checkCaptcha($captcha, $rcode, $level, $del))
			{
				$this->throwCaptchaException(self::CAPTCHA_ERROR);
			}
		}
		else
		{
			$this->throwCaptchaException(self::CAPTCHA_ERROR);
		}
		return true;
	}

	public function throwCaptchaException($exceptionNo)
	{
		if ($exceptionNo == self::CAPTCHA_COMMON || $exceptionNo == self::CAPTCHA_ADVANCE)
		{
			throw new Exception("common:need captcha", "19008");
		}
		else
		{
			throw new Exception("common:captcha error", "19006");
		}
	}

	/**
	 * 取验证码图片
	 * @param $rcode: 随机数
	 *        $length: 验证码长度 4-英文波浪4个 5-英文波浪5个加随机线条
	 */
	public function getCaptchaImg($length, $rcode, $action = "")
	{
		$width = $this->width;
		$height = $this->height;
		$scale = 2;

		$captchaLevel = self::CAPTCHA_COMMON;
		if (strlen($action))
		{
			$captchaLevel = Tool_Limit::getCaptchaLevel($action);
			if ($captchaLevel == self::CAPTCHA_ADVANCE)
			{
				$length = 5;
			}
		}

		//创建图像
		$im = imagecreatetruecolor($width * $scale, $height * $scale);

		//前景和背景色
		$forecolor = imagecolorallocate($im, $this->forecolor[0], $this->forecolor[1], $this->forecolor[2]);
		$backcolor = imagecolorallocate($im, $this->backcolor[0], $this->backcolor[1], $this->backcolor[2]);
		imagefilledrectangle($im, 0, 0, $width * $scale, $height * $scale, $backcolor);

		//画干扰折线
		if ($length == 5)
		{
			$this->drawBrokenLine($im, $width, $height, $scale, $forecolor);
		}

		//写验证码
		$text = $this->getCaptchaText($length);
		$this->writeEnhanceText($im, $text, $forecolor, $width, $height, $scale);
		//$this->waveImage($im, $width, $height, $scale);
		//压缩图
		$imResampled = imagecreatetruecolor($width, $height);
		imagecopyresampled($imResampled, $im, 0, 0, 0, 0, $width, $height, $width * $scale, $height * $scale);
		imagedestroy($im);
		$im = $imResampled;

		//缓存验证码
		$this->memcache->set($this->prefix . $rcode, strtolower($text), 600);
		$this->memcache->set($this->prefixLevel . $rcode, $captchaLevel, 600);
		Tool_Log::addDebugLog('captcha', strtolower($text));

		//输出图片
		header("Content-type: image/gif");
		imagegif($im, null, 80);
		imagedestroy($im);
		return true;
	}

	/**
	 * 取验证码文本
	 */
	private function getCaptchaText($length)
	{
		$str = "2345678ABCDEFGHJKLMNPQRSTUVWXYZabcdefhjkmnpqrstuvwxyz";
		$len = strlen($str);
		$text = '';
		for ($i = 0; $i < $length; $i++)
		{
			$text .= $str[mt_rand() % $len];
		}
		return $text;
	}

	/**
	 * 画干扰折线
	 */
	private function drawBrokenLine($im, $width, $height, $scale, $forecolor)
	{
		//画一条折线(起始x-0, y-中部))
		$x = 0;
		$y = $height * $scale / 2 + rand(-10, 10);
		$len = rand($width * $scale * 0.6, $width * $scale * 0.8);
		for ($i = 0; $i < $len / 5; $i++)
		{
			$x1 = $x + 8;
			$y1 = $y + rand(-12, 12) + sin(rand(-1, 1));

			if ($y1 > 90)
			{
				$y1 -= 30;
			}
			if ($y1 < 20)
			{
				$y1 += 30;
			}
			$this->imagelinethick($im, $x, $y, $x1, $y1, $forecolor, rand(2, 4));
			$x = $x1;
			$y = $y1;
		}

		//画一条折线(起始x-中部, y-中部)
		$x = $width * $scale * 0.65;
		$y = $height * $scale / 2 + rand(-10, 10);
		$len = rand($width * $scale * 0.2, $width * $scale * 0.3);
		for ($i = 0; $i < $len / 16; $i++)
		{
			$x1 = $x + 16;
			$y1 = $y + rand(-8, 8) + sin(rand(-1, 1));
			$this->imagelinethick($im, $x, $y, $x1, $y1, $forecolor, rand(2, 4));
			$x = $x1;
			$y = $y1;
		}
	}

	/**
	 * 画一条有宽度的线段
	 */
	private function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
	{
		if ($thick == 1)
		{
			return imageline($image, $x1, $y1, $x2, $y2, $color);
		}

		$t = $thick / 2 - 0.5;
		if ($x1 == $x2 || $y1 == $y2)
		{
			return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
		}

		$k = ($y2 - $y1) / ($x2 - $x1);
		$a = $t / sqrt(1 + pow($k, 2));
		$points = array(
			round($x1 - (1 + $k) * $a), round($y1 + (1 - $k) * $a),
			round($x1 - (1 - $k) * $a), round($y1 - (1 + $k) * $a),
			round($x2 + (1 + $k) * $a), round($y2 - (1 - $k) * $a),
			round($x2 + (1 - $k) * $a), round($y2 + (1 + $k) * $a),
		);
		imagefilledpolygon($image, $points, 4, $color);
		return imagepolygon($image, $points, 4, $color);
	}

	/**
	 * 写文本到图片中，具有字体和旋转
	 */
	private function writeEnhanceText($image, $text, $forecolor, $width, $height, $scale)
	{
		$length = strlen($text);

		//计算每个字符的旋转
		$rotates = array();
		$maxRotation = 18;
		for ($i = 0; $i < $length; $i++)
		{
			$rotates[] = rand($maxRotation * (-1), $maxRotation);
		}

		//字符大小补偿(每个9%)
		$maxWordLength = 8;
		$lettersMissing = $maxWordLength - $length;
		$fontSizefactor = 1 + ($lettersMissing * 0.09);

		//逐个字符写入图片
		$x = 15 * $scale;
		$y = round(($height * 27 / 38) * $scale);
		$maxRotation = 24;
		for ($i = 0; $i < $length; $i++)
		{
			$fontcfg = $this->fonts[array_rand($this->fonts)];
			$fontfile = CORE_DATA_PATH . '/font/' . $fontcfg['font'];
			$fontsize = rand($fontcfg['minSize'], $fontcfg['maxSize']) * $scale * $fontSizefactor;
			$fontsize = intval($fontsize * $width / 120) + 1;
			$letter = $text[$i];
			$degree = $rotates[$i];
			$spacing = 0;
			if ($i > 0)
			{
				$spacing = $this->getSpacing($rotates[$i - 1], $rotates[$i]);
			}
			$coords = imagettftext($image, $fontsize, $degree, $x, $y, $forecolor, $fontfile, $letter);
			$x += ($coords[2] - $x) + ($spacing * $scale);
		}
	}

	/**
	 * 获取字符间距
	 */
	private function getSpacing($rotate1, $rotate2)
	{
		$spacing = rand(-3, -5);
		if ($rotate1 * $rotate2 < -225)
		{
			$spacing = 0;
		}
		else if ($rotate1 * $rotate2 < -400)
		{
			$spacing = 1;
		}
		else if ($rotate1 * $rotate2 < -625)
		{
			$spacing = 2;
		}
		else if ($rotate1 * $rotate2 <= -900)
		{
			$spacing = 3;
		}

		return $spacing;
	}

	/**
	 * 图片波浪化
	 */
	private function waveImage($image, $width, $height, $scale)
	{
		$Yperiod = 12;
		$Xperiod = 11;
		$Yamplitude = 9;
		$Xamplitude = 5;

		//x方向
		$xp = $scale * $Xperiod * rand(1, 3);
		$k = rand(0, 100);
		for ($i = 0; $i < $width * $scale; $i++)
		{
			imagecopy($image, $image, $i - 1, sin($k + $i / $xp) * ($scale * $Xamplitude), $i, 0, 1, $height * $scale);
		}

		//y方向
		$yp = $scale * $Yperiod * rand(1, 2);
		$k = rand(0, 100);
		for ($i = 0; $i < ($height * $scale); $i++)
		{
			imagecopy($image, $image, sin($k + $i / $yp) * ($scale * $Yamplitude), $i - 1, 0, $i, $width * $scale, 1);
		}
	}

	public function getCaptchaImageEx($rcode, $length = 4)
	{
		//随机生成一个4位数的数字验证码
		$num = "";
		for ($i = 0; $i < $length; $i++)
		{
			$num .= rand(0, 9);
		}
		//将生成的验证码写入mc，备验证页面使用
		$mkey = $this->mcPrefix . $rcode;
		$this->memCache->setMemCache($mkey, $num, 600);

		header("Content-type: image/PNG");
		//创建图片，定义颜色值
		srand((double) microtime() * 1000000);
		$im = imagecreate(60, 20);
		$black = ImageColorAllocate($im, 0, 0, 0);
		$gray = ImageColorAllocate($im, 200, 200, 200);
		imagefill($im, 0, 0, $gray);

		//随机绘制两条虚线，起干扰作用
		$style = array($black, $black, $black, $black, $black, $gray, $gray, $gray, $gray, $gray);
		imagesetstyle($im, $style);
		$y1 = rand(0, 20);
		$y2 = rand(0, 20);
		$y3 = rand(0, 20);
		$y4 = rand(0, 20);
		imageline($im, 0, $y1, 60, $y3, IMG_COLOR_STYLED);
		imageline($im, 0, $y2, 60, $y4, IMG_COLOR_STYLED);

		//在画布上随机生成大量黑点，起干扰作用;
		for ($i = 0; $i < 80; $i++)
		{
			imagesetpixel($im, rand(0, 60), rand(0, 20), $black);
		}
		//将四个数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
		$strx = rand(3, 8);
		for ($i = 0; $i < 4; $i++)
		{
			$strpos = rand(1, 6);
			imagestring($im, 5, $strx, $strpos, substr($num, $i, 1), $black);
			$strx+=rand(8, 12);
		}
		imagePNG($im);
		imageDestroy($im);
	}

	public function checkCaptchaEx($captcha, $rcode, $del = false)
	{
		$mkey = $this->mcPrefix . $rcode;
		$res = $this->memCache->getMemCache($mkey);
		if ($res != $captcha)
		{
			return false;
		}
		if ($del)
		{
			$this->memCache->delMemCache($mkey);
		}

		return true;
	}

}

?>
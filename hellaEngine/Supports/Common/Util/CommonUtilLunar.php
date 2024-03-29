<?php
namespace hellaEngine\Supports\Common\Util;
/**
 * @package common
 * @subpackage util
 * @author kain
 *
 */
/**
*   农历类
*/
class CommonUtilLunar
{
	var   $sy;
	var   $sm;
	var   $sd;
	var   $year;
	var   $month;
	var   $day;
	var   $isLeap;
	var   $yearCyl;
	var   $monCyl;
	var   $dayCyl;
	var   $term;
	var   $termyearCyl;

	/*
		1900 - 2050 年 农历数据
		对应阳历
		1900-01-31 --> 2051-02-10
		0000 0000 0000 0000 0000
		1  -- 没用
		2  -- 没用
		3  -- 没用
		4  -- 如果当年是闰年，为1表示闰月有30，为0表示闰月29天
		5  -- 为1表示1月为30天，否则29天
		6  -- 为1表示2月为30天，否则29天
		7  -- 为1表示3月为30天，否则29天
		8  -- 为1表示4月为30天，否则29天
		9  -- 为1表示5月为30天，否则29天
		10 -- 为1表示6月为30天，否则29天
		11 -- 为1表示7月为30天，否则29天
		12 -- 为1表示8月为30天，否则29天
		13 -- 为1表示9月为30天，否则29天
		14 -- 为1表示10月为30天，否则29天
		15 -- 为1表示11月为30天，否则29天
		16 -- 为1表示12月为30天，否则29天
		17 - 20 -- 当年闰几月 0表示无闰月
	*/
	var   $lunarInfo   =   array(
		0x04bd8,0x04ae0,0x0a570,0x054d5,0x0d260,0x0d950,0x16554,0x056a0,0x09ad0,0x055d2,
		0x04ae0,0x0a5b6,0x0a4d0,0x0d250,0x1d255,0x0b540,0x0d6a0,0x0ada2,0x095b0,0x14977,
		0x04970,0x0a4b0,0x0b4b5,0x06a50,0x06d40,0x1ab54,0x02b60,0x09570,0x052f2,0x04970,
		0x06566,0x0d4a0,0x0ea50,0x06e95,0x05ad0,0x02b60,0x186e3,0x092e0,0x1c8d7,0x0c950,
		0x0d4a0,0x1d8a6,0x0b550,0x056a0,0x1a5b4,0x025d0,0x092d0,0x0d2b2,0x0a950,0x0b557,
		0x06ca0,0x0b550,0x15355,0x04da0,0x0a5b0,0x14573,0x052b0,0x0a9a8,0x0e950,0x06aa0,
		0x0aea6,0x0ab50,0x04b60,0x0aae4,0x0a570,0x05260,0x0f263,0x0d950,0x05b57,0x056a0,
		0x096d0,0x04dd5,0x04ad0,0x0a4d0,0x0d4d4,0x0d250,0x0d558,0x0b540,0x0b6a0,0x195a6,
		0x095b0,0x049b0,0x0a974,0x0a4b0,0x0b27a,0x06a50,0x06d40,0x0af46,0x0ab60,0x09570,
		0x04af5,0x04970,0x064b0,0x074a3,0x0ea50,0x06b58,0x055c0,0x0ab60,0x096d5,0x092e0,
		0x0c960,0x0d954,0x0d4a0,0x0da50,0x07552,0x056a0,0x0abb7,0x025d0,0x092d0,0x0cab5,
		0x0a950,0x0b4a0,0x0baa4,0x0ad50,0x055d9,0x04ba0,0x0a5b0,0x15176,0x052b0,0x0a930,
		0x07954,0x06aa0,0x0ad50,0x05b52,0x04b60,0x0a6e6,0x0a4e0,0x0d260,0x0ea65,0x0d530,
		0x05aa0,0x076a3,0x096d0,0x04bd7,0x04ad0,0x0a4d0,0x1d0b6,0x0d250,0x0d520,0x0dd45,
		0x0b5a0,0x056d0,0x055b2,0x049b0,0x0a577,0x0a4b0,0x0aa50,0x1b255,0x06d20,0x0ada0,
		0x14b63);
	var   $sTermInfo   =   array(
		0,21208,42467,63836,85337,107014,128867,150921,
		173149,195551,218072,240693,263343,285989,308563,331033,
		353350,375494,397447,419210,440795,462224,483532,504758);

	/**
	*   传回农历   y年某节气为几号
	*/
	function sTerm($year,$n)   {
		$dateVal = ( 31556925974.7*($year-1900) + $this->sTermInfo[$n]*60000  ) - 2208549300000;
		if ($dateVal < 0)
		{
			$yms = 0;
			for($y=1969; ; $y--)
			{
				$yms -= Common_Util_Sunar::lYearDays($y) * 24 * 3600000;
				if ($yms < $dateVal)
				{
					break;
				}
			}
		}
		else
		{
			$yms = 0;
			for($y=1970; ; $y++)
			{
				$yms += Common_Util_Sunar::lYearDays($y) * 24 * 3600000;
				if ($yms > $dateVal)
				{
					break;
				}
			}
			$yms -= Common_Util_Sunar::lYearDays($y) * 24 * 3600000;
		}

		$mms = 0;
		for ($m=1; $m<=12; $m++)
		{
			$mms += Common_Util_Sunar::monthDays($year, $m) * 24 * 3600000;
			if ($yms + $mms > $dateVal)
			{
				break;
			}
		}
		$mms -= Common_Util_Sunar::monthDays($year, $m) * 24 * 3600000;
		$days = Common_Util_Sunar::monthDays($year, $m);
		$dms = 0;
		for ($d=1; $d<=$days; $d++)
		{
			$dms += 24 * 3600000;
			if ($yms + $mms + $dms > $dateVal)
			{
				break;
			}
		}
		return $d;
	}
	/**
	*   传回农历   y年的总天数
	*/
	function   lYearDays($y)   {
		$sum   =   348;
		for($i=0x8000;   $i>0x8;   $i>>=1)
			$sum   +=   ($this->lunarInfo[$y-1900]   &   $i)?   1:   0;
		return   $sum+$this->leapDays($y);
	}
	/**
	*   传回农历   y年闰月的天数
	*/
	function   leapDays($y)   {
		if($this->leapMonth($y))
			return   ($this->lunarInfo[$y-1900]   &   0x10000)?   30:   29;
		else   return   0;
	}
	/**
	*   传回农历   y年闰哪个月   1-12   ,   没闰传回   0
	*/
	function   leapMonth($y)   {
		return   $this->lunarInfo[$y-1900]   &   0xf;
	}
	/**
	*   传回农历   y年m月的总天数
	*/
	function   monthDays($y,$m)   {
		return   ($this->lunarInfo[$y-1900]   &   (0x10000>>$m))?   30:   29;
	}
	/**
	*   创建农历日期对象
	*/
	function   Common_Util_Lunar($year,$month,$day)   {
		if ($year < 1900 || $year > 2051)
		{
			return false;
		}
		if ($year == 1900)
		{
			if ($month == 1 && $day != 31)
			{
				return false;
			}
		}
		if ($year == 2051)
		{
			if ($month > 2)
			{
				return false;
			}
			if ($month == 2 && $day > 10)
			{
				return false;
			}
		}

		$this->sy = $year;
		$this->sm = $month;
		$this->sd = $day;

		$this->term = -1;
		$jDay = $this->sTerm($this->sy, ($this->sm - 1) * 2);
		if ($jDay == $this->sd)
		{
			$this->term = ($this->sm - 1) * 2;
		}
		else
		{
			$qDay = $this->sTerm($this->sy, ($this->sm - 1) * 2 + 1);
			if ($qDay == $this->sd)
			{
				$this->term = ($this->sm - 1) * 2 + 1;
			}
		}

		$offset = 0;
		if ($this->sy == 1900)
		{
			if ($this->sm > 1)
			{
				for ($m=2; $m<$this->sm; $m++)
				{
					$offset += Common_Util_Sunar::monthDays($this->sy, $m);
				}
				$offset += $this->sd;
			}
		}
		else
		{
			$offset = Common_Util_Sunar::lYearDays(1900) - 31;
			for ($y=1901; $y<$this->sy; $y++)
			{
				$offset += Common_Util_Sunar::lYearDays($y);
			}
			for ($m=1; $m<$this->sm; $m++)
			{
				$offset += Common_Util_Sunar::monthDays($this->sy, $m);
			}
			$offset += $this->sd;
		}
		$this->dayCyl = $offset + 40;

		$this->monCyl = 12;
		$this->monCyl += ($this->sy - 1900) * 12;
		$this->monCyl += $this->sm - 1;
		if ($this->sd >= $jDay)
		{
			$this->monCyl += 1;
		}

		$ydays = $this->lYearDays(1900);
		if ($ydays > $offset)
		{
			$this->year = 1900;
			$ydays = 0;
		}
		else
		{
			for ($y=1901; ; $y++)
			{
				$ydays += $this->lYearDays($y);
				if ($ydays > $offset)
				{
					break;
				}
			}
			$this->year = $y;
			$ydays -= $this->lYearDays($y);
		}

		$this->isLeap = false;
		$lmon = $this->leapMonth($this->year);
		$mdays = 0;
		for ($m=1; $m<=12; $m++)
		{
			$mdays += $this->monthDays($this->year, $m);
			if ($mdays > $offset - $ydays)
			{
				break;
			}
			if ($m == $lmon)
			{
				$mdays += $this->leapDays($this->year);
				if ($mdays > $offset - $ydays)
				{
					$this->isLeap = true;
					break;
				}
			}
		}
		$this->month = $m;
		if ($this->isLeap)
		{
			$mdays -= $this->leapDays($this->year);
		}
		else
		{
			$mdays -= $this->monthDays($this->year, $m);
		}
		$this->day = $offset - $ydays - $mdays + 1;
		$this->yearCyl = $this->year + 56;
		$this->termyearCyl = $this->sy + 56;

		if ($this->sm == 1)
		{
			$this->termyearCyl --;
		}
		else if ($this->sm == 2)
		{
			if ($this->sd < $jDay)
			{
				$this->termyearCyl --;
			}
		}
	}

	/**
	*   干支年份
	*/
	function   cyclical($num)   {
		$Gan   =   Array("甲","乙","丙","丁","戊","己","庚","辛","壬","癸");
		$Zhi   =   Array("子","丑","寅","卯","辰","巳","午","未","申","酉","戌","亥");
		return   $Gan[$num%10].$Zhi[$num%12];
	}

	/**
	*   生肖
	*/
	function   animal($num)   {
		$animal   =   Array("鼠","牛","虎","兔","龙","蛇","马","羊","猴","鸡","狗","猪");
		return   $animal[$num%12];
	}

	/**
	*   中文月份
	*/
	function   cMon($m)   {
		$nStr   =   array('正','二','三','四','五','六','七','八','九','十','冬','腊');
		return $nStr[$m-1];
	}
	/**
	*   中文日期
	*/
	function   cDay($d)   {
		$nStr1   =   array('一','二','三','四','五','六','七','八','九','十');
		$nStr2   =   array('初','十','廿','卅');

		switch($d)   {
		case   10:
			$s   =   '初十';
			break;
		case   20:
			$s   =   '二十';
			break;
		case   30:
			$s   =   '三十';
			break;
		default   :
			$s   =   $nStr2[floor($d/10)];
			$s   .=   $nStr1[$d%10-1];
		}
		return   $s;
	}
	function   cTerm($n)   {
		$solarTerm = array("小寒","大寒","立春","雨水","惊蛰","春分","清明","谷雨","立夏","小满","芒种","夏至","小暑","大暑","立秋","处暑","白露","秋分","寒露","霜降","立冬","小雪","大雪","冬至");
		if ($n >= 0 && $n < 24)
		{
			return $solarTerm[$n];
		}
		return "";
	}
	/**
	*   输出，根据需要直接修改本函数或在派生类中重写本函数
	*/
	function   display()   {
		$nl   =   sprintf("%02d-%02d-%02d, %s年 %s%s月 %s -- %s %s %s -- %s -- %s\n",
			$this->sy,
			$this->sm,
			$this->sd,
			$this->year,
			($this->isLeap?"闰":""), $this->cMon($this->month),
			$this->cDay($this->day),
			$this->cyclical($this->yearCyl),
			$this->cyclical($this->monCyl),
			$this->cyclical($this->dayCyl),
			$this->cTerm($this->term),
			$this->cyclical($this->termyearCyl)
			);
		echo   $nl;
	}
}     //   农历类定义结束

class Common_til_Sunar
{
	function isLeapYear($y)   {
		return (($y%4 == 0 && $y%100 != 0) || $y%400 == 0);
	}
	function lYearDays($y)   {
		if(Common_Util_Sunar::isLeapYear($y))
		{
			return 366;
		}
		return 365;
	}
	function   monthDays($y,$m)   {
		$dayarr = array(31,28,31,30,31,30,31,31,30,31,30,31);
		if ($m == 2)
		{
			if(Common_Util_Sunar::isLeapYear($y))
			{
				return 29;
			}
		}
		return $dayarr[$m-1];
	}
}

/*
$clunar = new CommonUtilLunar(0, 0, 0);
for ($y = 1900; $y <= 2050; $y ++)
{
	echo $y." ";
	for ($i=0; $i<24; $i++)
	{
		echo $clunar->sTerm($y, $i);
		echo " ";
	}
	echo "\n";
}
exit;
*/

/*
$y = 1900;
$m = 1;
$d = 31;
$clunar = new CommonUtilLunar($y, $m, $d);
$clunar->display();

for ($m=2; $m<=12; $m++)
{
	$days = Common_Util_Sunar::monthDays($y, $m);
	for ($d=1; $d<=$days; $d++)
	{
		$clunar = new CommonUtilLunar($y, $m, $d);
		$clunar->display();
	}
}

for ($y=1901; $y <= 2050; $y++)
{
	for ($m=1; $m<=12; $m++)
	{
		$days = Common_Util_Sunar::monthDays($y, $m);
		for ($d=1; $d<=$days; $d++)
		{
			$clunar = new CommonUtilLunar($y, $m, $d);
			$clunar->display();
		}
	}
}

$y=2051;
for ($m=1; $m<=2; $m++)
{
	$days = Common_Util_Sunar::monthDays($y, $m);
	if ($m = 2)
	{
		$days = 10;
	}
	for ($d=1; $d<=$days; $d++)
	{
		$clunar = new CommonUtilLunar($y, $m, $d);
		$clunar->display();
	}
}
*/

?>
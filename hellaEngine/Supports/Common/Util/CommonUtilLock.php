<?php
namespace hellaEngine\Supports\Common\Util;
/**
 * lock类
 *
 * @package common
 * @subpackage util
 * @author kain
 *
 */
class CommonUtilLock implements CommonUtilLockInterface
{
    private $key;
    private $lockhandler;
    private $locked;
    private $lockpath;

    function __construct($key = NULL)
    {
        $this->set_key($key);
        $this->lockpath = $_SERVER ['DOCUMENT_ROOT'] . "/locks/";
    }

    function __destruct()
    {
        $this->__unlock();
    }

    public function set_key($value)
    {
        $this->key = strval($value);
    }

    /**
     * 获取锁
     *
     * @return string
     */
    function get_key()
    {
    }


    /**
     * 加锁
     *
     * @param number $timeoutsec
     *            超时秒数
     * @return boolean False加锁失败
     */
    public function lock($timeoutsec = 1, $sync = TRUE)
    {
        return $this->__lock($timeoutsec, $sync);
    }

    private function __lock($timeoutsec = 1, $sync = TRUE)
    {
        $timeoutsec = intval($timeoutsec);

        if (empty ($this->key)) {
            dump("lock key error!", FALSE, 2);
            exit ();
        }
        if (!$this->lockhandler) {
            $this->lockhandler = fopen($this->lockpath . $this->key . ".lock", "w");
        }
        if (!$this->lockhandler) {

            dump("open lockfile failed!", FALSE, 2);
            return FALSE;
        }

        $locked = FALSE;
        $startTime = CommonUtilTime::getCurrenttime();

        do {
            // functionsDump("begin lock");
            $locked = flock($this->lockhandler, LOCK_EX | LOCK_NB);
            if (!$locked) {
                // functionsDump ( "lock failed" . (CommonUtilTime::getCurrenttime () - $startTime) );
                usleep(round(rand(0, 100) * 1000));
            }
        } while ($sync && !$locked && (CommonUtilTime::getCurrenttime() - $startTime) < $timeoutsec);

        if ($locked) {
            $this->locked = true;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 解锁
     */
    public function unlock()
    {
        return $this->__unlock();
    }

    public function __unlock()
    {
        if (!$this->locked) {
            return TRUE;
        }
        if ($this->lockhandler) {
            flock($this->lockhandler, LOCK_UN);
            fclose($this->lockhandler);
        }
        return TRUE;
    }
}

// $lockfp = CommonUtilLock::check($argv[0]);

?>
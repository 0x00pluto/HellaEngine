<?php

namespace hellaEngine\Supports\utils\schedule;

/**
 *
 * 任务结构
 *
 * @author zhipeng
 *
 */
interface ITask {
	/**
	 * 更新
	 *
	 * @param int $sheduleTime
	 *        	调用当时的时间点
	 */
	function onSchedule($sheduleTime);
}
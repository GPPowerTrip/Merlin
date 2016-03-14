<?php
/**
 * Created by PhpStorm.
 * User: Jaime
 * Date: 07/01/2016
 * Time: 10:51
 */

namespace merlin;
use Redis;

class RedisFactory {
	/**
	 * @var Redis
	 */
	private static $instance = null;

	/**
	 * @return Redis
	 */
	public static function getInstance() {
		if(RedisFactory::$instance==null) {
			RedisFactory::$instance = new Redis() or die("Cannot load Redis module.");
			RedisFactory::$instance->connect('localhost', 6379);
		}
		return RedisFactory::$instance;
	}
}
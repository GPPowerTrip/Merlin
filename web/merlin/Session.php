<?php
/**
 * Created by PhpStorm.
 * User: Jaime
 * Date: 07/01/2016
 * Time: 10:58
 */

namespace merlin;

define('SESSION_COOKIE_NAME', 'session_id');
define('SESSIONS_REDIS_KEY', 'SESSION');


class Session {
	/**
	 * @var Session
	 */
	private static $instance;
	/**
	 * @var \Redis
	 */
	private $redis;
	/**
	 * @var string
	 */
	private $uuid;
	/**
	 * @var array
	 */
	private $data;

	/**
	 * Session constructor.
	 */
	private function __construct() {
		$this->redis = RedisFactory::getInstance();
	}

	function __destruct() {
		$this->persistSession();
	}


	/**
	 * @return Session
	 */
	public static function getInstance() {
		if(Session::$instance==null){
			Session::$instance = new Session();
			Session::$instance->start();
		}
		return Session::$instance;
	}




	/**
	 * @return string
	 */
	public function getUuid() {
		return $this->uuid;
	}





	/**
	 *
	 */
	public function start() {
		if(isset($_COOKIE[SESSION_COOKIE_NAME]) && $this->sessionExists($_COOKIE[SESSION_COOKIE_NAME]) ) {
			$this->uuid = $_COOKIE[SESSION_COOKIE_NAME];
			$this->loadSession();
		} else {
			$this->createNewSession();
			$this->writeSession();
		}

	}


	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get($key) {
		$value = isset($this->data[$key])?$this->data[$key]:null;
		return $value;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function end(){
		$this->redis->hDel(SESSIONS_REDIS_KEY, $this->uuid);
		$this->createNewSession();
		$this->writeSession();
	}

	private function createNewSession() {
		$this->uuid = $this->generateUUID();
		$this->data = array();

	}

	private function writeSession(){
		setcookie(SESSION_COOKIE_NAME, $this->uuid, strtotime('+30 days'));
		$this->persistSession();
	}

	private function persistSession(){
		$this->redis->hSet(SESSIONS_REDIS_KEY, $this->uuid, json_encode($this->data, JSON_PRETTY_PRINT));
	}

	private function loadSession(){
		$this->data = json_decode($this->redis->hGet(SESSIONS_REDIS_KEY, $this->uuid), true);
	}



	private function generateUUID() {
		return uniqid(rand(0,2000000000));
	}

	private function sessionExists($uuid) {
		return $this->redis->hExists(SESSIONS_REDIS_KEY, $uuid);
	}


}
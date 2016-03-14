<?php
/**
 * Created by PhpStorm.
 * User: Jaime
 * Date: 07/01/2016
 * Time: 12:32
 */

namespace merlin;

define('ERROR_SESSION_KEY', 'ERRORS');

class Logger {
	/**
	 * @var Logger
	 */
	private static $instance;
	/**
	 * @var Session
	 */
	private $session;

	/**
	 * Logger constructor.
	 */
	private function __construct() {
		$this->session = Session::getInstance();
	}

	/**
	 * @return Logger
	 */
	public static function getInstance() {
		if(Logger::$instance==null) Logger::$instance = new Logger();
		return Logger::$instance;
	}

	/**
	 * @param string $string
	 */
	public function error($string) {
		if( $this->session->get(ERROR_SESSION_KEY) == null) $this->session->set(ERROR_SESSION_KEY, array());
		$errorArray = $this->session->get(ERROR_SESSION_KEY);
		$errorArray[] = $string;
		$this->session->set(ERROR_SESSION_KEY, $errorArray);
	}



	public function getErrors(){
		if( $this->session->get(ERROR_SESSION_KEY) == null) return array();
		$errors = $this->session->get(ERROR_SESSION_KEY);
		$this->session->set(ERROR_SESSION_KEY, array());
		return $errors;
	}





}
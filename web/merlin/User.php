<?php
/**
 * Created by PhpStorm.
 * User: Jaime
 * Date: 07/01/2016
 * Time: 11:33
 */

namespace merlin;


use JsonSerializable;

class User implements JsonSerializable {
	/**
	 * @var string
	 */
	private $username;
	/**
	 * @var string
	 */
	private $password;

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @param string $username
	 * @return User
	 */
	public function setUsername($username) {
		$this->username = $username;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return User
	 */
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}


	/**
	 * Specify data which should be serialized to JSON
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	function jsonSerialize() {
		return array(
				'username' => $this->getUsername(),
				'password' => $this->getPassword()
			);
	}
}
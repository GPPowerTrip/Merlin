<?php
/**
 * Created by PhpStorm.
 * User: Jaime
 * Date: 07/01/2016
 * Time: 11:40
 */

namespace merlin;


/**
 *
 */
define('USER_REDIS_KEY', 'USERS');

class UserManager {

	/**
	 * @param User $user
	 * @throws \Exception
	 */
	public static function createUser($user) {
		$redis = RedisFactory::getInstance();
		if( UserManager::userExists($user->getUsername()) ) {
			throw new \Exception("User " . $user->getUsername() . " already exists");
		}
		$redis->hset(USER_REDIS_KEY, $user->getUsername(), json_encode($user, JSON_PRETTY_PRINT));
	}

	/**
	 * @param $username
	 * @return bool
	 */
	private static function userExists($username) {
		$redis = RedisFactory::getInstance();
		return $redis->hExists(USER_REDIS_KEY, $username);
	}

	/**
	 * @param User $user
	 * @return bool
	 */
	public static function authenticate($user) {
		$luser = self::loadUser($user->getUsername());
		return ($luser!=null && $luser->getUsername() == $user->getUsername() && $luser->getPassword() == $user->getPassword());
	}

	/**
	 * @param string $username
	 * @return User
	 */
	public static function loadUser($username) {
		$redis = RedisFactory::getInstance();
		if( !UserManager::userExists($username) ) return null;
		$userArray = json_decode($redis->hGet(USER_REDIS_KEY, $username), true);
		$user = new User();

		return $user
			->setUsername($userArray['username'])
			->setPassword($userArray['password']);
	}

	/**
	 * @return User
	 */
	public static function getLoggedUser(){
		return UserManager::loadUser(Session::getInstance()->get('user'));
	}

}
<?php
/**
 * Created by PhpStorm.
 * User: Jaime
 * Date: 07/01/2016
 * Time: 14:55
 */

	require '../../merlin/RedisFactory.php';

	define('ADJECTIVES', 'ADJECTIVES');
	define('NOUNS', 'NOUNS');

	$redis = \merlin\RedisFactory::getInstance();


	$redis->del(ADJECTIVES);
	$redis->del(NOUNS);

	/**
	 * @param Redis $redis
	 * @param string $pathToFile
	 * @param string $setKey
	 **/
 	function loadLinesToSet($redis, $pathToFile, $setKey){
		$lines = array_map(
			function($element){
				return trim(strtolower($element));
			},
			explode("\n", file_get_contents($pathToFile))
		);
	    foreach( $lines as $line ) $redis->sAdd($setKey, $line);
	}

	loadLinesToSet($redis, 'adjective.txt', ADJECTIVES);
	loadLinesToSet($redis, 'noun.txt', NOUNS);


	$a = $redis->sRandMember(ADJECTIVES);
	$n = $redis->sRandMember(NOUNS);

	echo preg_replace("/[^a-zA-Z0-9_]+/", "", "{$a}_{$n}");



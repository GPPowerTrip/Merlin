<?php
/**
 * Created by PhpStorm.
 * User: Jaime
 * Date: 07/01/2016
 * Time: 13:26
 */

namespace merlin;

//define('DOCKER_REMOTE', 'DOCKER_HOST="tcp://192.168.1.203:2376" ');
//define('DOCKER_CMD_PREFIX', 'cd /var/www/powertrip/gp/merlin/docker && ' . DOCKER_REMOTE . ' ');

define('PATH_TO_COMPOSE', 'docker');
define('DOCKER_REMOTE', '');
define('DOCKER_CMD_PREFIX', 'cd ' . PATH_TO_COMPOSE . ' && ');


class Crane {
	private static $instance;


	/**
	 * Crane constructor.
	 */
	private function __construct() {
	}

	/**
	 * @return Crane
	 */
	public static function getInstance() {
		if(self::$instance==null) self::$instance = new self();
		return self::$instance;
	}

	public function startExcalibot($uuid) {
		$cmd = DOCKER_CMD_PREFIX . "docker-compose -p $uuid up -d";
		$output = shell_exec($cmd);
		return $output;
	}

	public function setBotCount($uuid, $count) {
		$cmd = DOCKER_CMD_PREFIX . "docker-compose -p $uuid scale knight=$count";
		$output = shell_exec($cmd);
		return $output;
	}

	public function destroyEverything($uuid) {
		$cmd = DOCKER_CMD_PREFIX . "docker-compose -p $uuid kill && " . \
			   DOCKER_CMD_PREFIX . "docker-compose -p $uuid rm -f";
		$output = shell_exec($cmd);
		return $output;
	}

	public function getLogs($containerName, $offset){
		$offset = intval($offset);
		$cmd = DOCKER_CMD_PREFIX . "docker logs $containerName |  awk 'NR>$offset'";
		$output = shell_exec($cmd);
		return $output;
	}

	public function listContainers($uuid){
		$cmd = DOCKER_CMD_PREFIX . "docker-compose -p $uuid ps | awk -F, 'BEGIN { FS = \"[ \\t\\n]+\" } { if (NR>2) { print \$1 } }'";
		$output = shell_exec($cmd);
		return array_map(
		function ($str){
			return trim($str);
		},
		explode("\n", trim($output)));
	}

	public function executeCommand($uuid, $command){
		$command = trim($command);
		$command = escapeshellcmd($command);
		$containers = $this->listContainers($uuid);
		$arthurUUID = null;
		foreach($containers as $c){
			if(strpos($c, 'arthur')){
				$arthurUUID = $c;
				break;
			}
		}
		$cmd = DOCKER_REMOTE . "docker run --rm --link $arthurUUID pmdcosta/client excalibot" . (($command!='help')?" $arthurUUID 8080":'') . " $command 2>&1";
		$output = shell_exec($cmd);
		return $output;
	}


}
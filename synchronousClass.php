<?php
require_once __DIR__."/vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class synchronous
{
	
	public $option;
	public $clientWebServer=array();
	public $remoteWebServer=array();
	protected $log;
	
	public function __construct($clientWebServer,$remoteWebServer) {
		
		$this->clientWebServer = $clientWebServer;
		$this->remoteWebServer = $remoteWebServer;
		
		$this->log = new Logger('synchronous');
		$this->log->pushHandler(new StreamHandler($this->clientWebServer["synchronousLog"], Logger::INFO));
		
		return true;
	}
	
	/**
	 * Hello application
	 */
	public function synchronousInfo($appName) {
		echo "Hello ".$appName."!\n";
		return true;
	}
	
	/**
	 * Parameter Displays
	 */
	public function parameterDisplay($parameter)
	{
		foreach($parameter as $key=>$param) {
			if ($key=="title" && !empty($param)) {
				echo $param."\n";
			} else {
				echo "\t".$key.": ".$param."\n";
			}
		}
		return true;
	}
	
	/**
	 * General-purpose execution
	 */
	public function getOption($option="") {
		
		/* If there is no option, execute it normally */
		if (empty($option))
		{
			/**
			 * Connect Test
			 * Connect with SSH2 public key authentication
			 */
			if ($this->remoteWebServer["authenticationMethod"] == "sshkey")
			{
				/* Connection test */
				$connection = ssh2_connect(
					$this->remoteWebServer["remoteHost"],
					$this->remoteWebServer["port"]
				);

				if ($connection!==false) {
					if (ssh2_auth_pubkey_file(
						$connection,
						$this->remoteWebServer["user"],
						$this->remoteWebServer["sshPubKeyPath"],
						$this->remoteWebServer["sshSecletKeyPath"], '')
					)
					{
						echo "Public Key Authentication Successful.\n";
						echo "SSH remote connection parameters are correct.\n";
					}

				} else {
					die("remote host: ".$this->remoteWebServer["remoteHost"]." or port: ".$this->remoteWebServer["port"]." are maybe wrong. \n");
				}
			}
			else if ($this->remoteWebServer["authenticationMethod"] == "password")
			{
				// commig soon
				die("It is not functioning yet.\n");
			}
			
			/* Run */
			$this->rsyncRun();
			

		}
		
		/* Exception execution if there is an option */
		else if ($option=="--info")
		{
			var_dump($this->clientWebServer);
			var_dump($this->remoteWebServer);
		}
		
		/* Rsync dry run */
		else if ($option=="--dryrun")
		{
			$this->rsyncDryRun();
		}
		
		
		
	}
	
	/**
	 * rsync
	 * Command example:
	 * rsync -avz --delete -e "ssh -i /home/user/.ssh/id_rsa -p 22" /from user@example.com:/to
	 */
	public function rsyncRun() {
	
		echo "Start rsync.....\n";
		$rsync = 
			"rsync -avz --delete --exclude-from=".$this->remoteWebServer["rsyncIgnore"]." -e 'ssh -i "
			.$this->remoteWebServer["sshSecletKeyPath"]." -p "
			.$this->remoteWebServer["port"]
			."' /home/stg_giants_ownersite "
			.$this->remoteWebServer["user"]
			."@".$this->remoteWebServer["remoteHost"]
			.":".$this->remoteWebServer["userDirectory"]." > ".$this->clientWebServer["clientWorkDir"]."/synchronousTmp.log";
		$e = shell_exec($rsync);

		$rsync_stdout = file_get_contents($this->clientWebServer["clientWorkDir"]."/synchronousTmp.log");
		$this->log->addInfo($rsync_stdout);
		echo $rsync_stdout."\n";
		
		return true;
	}
	
	/**
	 * rsync dry run
	 * Command example:
	 * rsync --checksum -navz --delete -e "ssh -i /home/user/.ssh/id_rsa -p 22" /from user@example.com:/to
	 */
	public function rsyncDryRun() {
	
		echo "Start rsync.....\n";
		$rsync = 
			"rsync --checksum -navz --delete --exclude-from=".$this->remoteWebServer["rsyncIgnore"]." -e 'ssh -i "
			.$this->remoteWebServer["sshSecletKeyPath"]." -p "
			.$this->remoteWebServer["port"]
			."' /home/stg_giants_ownersite "
			.$this->remoteWebServer["user"]
			."@".$this->remoteWebServer["remoteHost"]
			.":".$this->remoteWebServer["userDirectory"]." > ".$this->clientWebServer["clientWorkDir"]."/synchronousTmp.log";
		$e = shell_exec($rsync);

		$rsync_stdout = file_get_contents($this->clientWebServer["clientWorkDir"]."/synchronousTmp.log");
		echo $rsync_stdout."\n";
		
		return true;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}




class option
{
	
	var $option = array();
	public function getOption() {
		
		$options = array(
			"--config-list" => array(
				"description" => "It is a list of connection information.",
				"result" => "No effect on execution",
				"type" => "Dry run",
			),
			"--info" => array(
				"description" => "It is a list of all information.",
				"result" => "No effect on execution",
				"type" => "Dry run",
			),
		
		
		);
	}
}




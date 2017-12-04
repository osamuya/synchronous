<?php
require_once __DIR__."/vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;



/**

[ Options List ]

--info		: Display all parameters being set
--dryrun	: Dry run (virtual execution) of the execution of synchronization.
--list		: Display a list of available execution options.

**/



class synchronous
{
	/* Propaties */
	public $option;
	public $clientWebServer=array();
	public $remoteWebServer=array();
	protected $log;
	protected $options;
	
	
	
	/**
	 * __construct
	 *
	 * clientWebServer's parameter setting
	 * remoteWebServer's parameter setting
	 * new Logger
	 * Logging path setting
	 *
	 * @category: test
	 */
	public function __construct($clientWebServer,$remoteWebServer) {
		
		$this->clientWebServer = $clientWebServer;
		$this->remoteWebServer = $remoteWebServer;
		
		$this->log = new Logger('synchronous');
		$this->log->pushHandler(new StreamHandler($this->clientWebServer["synchronousLog"], Logger::INFO));
		
		return true;
	}
	
	
	
	/**
	 * function: synchronousInfo()
	 * Hello application
	 *
	 * @category: test
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
	 * ==========================
	 * General-purpose execution
	 * ==========================
	 * function: getOption()
	 *
	 * Command example: rsync -avz --delete -e "ssh -i /home/user/.ssh/id_rsa -p 22" /from user@example.com:/to
	 * @category: exec
	 *
	 */
	public function getOption($option="") {
		
		/* If there is no option, execute it normally */
		if (empty($option))
		{
			/* Connect Test */
			$this->connectTest();
			
			/* Run */
			$this->rsyncRun();
			
			
			/**
			 * DB dump & restore
			 *
			 * SSH接続
			 *
			 */
			
		}
		
		/* Exception execution if there is an option */
		else if ($option=="--info")
		{
			var_dump($this->clientWebServer);
			var_dump($this->remoteWebServer);
			die("Generally OK.\n");
		}
		
		/* Rsync dry run */
		else if ($option=="--dryrun")
		{
			$this->rsyncDryRun();
			die("Generally OK.\n");
		}
		
		/* Rsync dry run */
		else if ($option=="--list")
		{
			$this->listingOptions();
			die("Generally OK.\n");
		}
		else {
			echo "There is no such option.\n";
			die("Generally OK.\n");
		}
		
	} // End getOption()
	
	
	/**
	 * function: connectTest()
	 * Connect with SSH2 public key authentication
	 * Command example: rsync -avz --delete -e "ssh -i /home/user/.ssh/id_rsa -p 22" /from user@example.com:/to
	 *
	 * @category: test
	 */
	public function connectTest() {
		
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
		return true;
		
	} // End connectTest()
	
	
	
	/**
	 * function: rsyncRun()
	 *
	 * Command example: rsync -avz --delete -e "ssh -i /home/user/.ssh/id_rsa -p 22" /from user@example.com:/to
	 * @category: exec
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
		
	} // End rsyncRun()
	
	
	
	
	/**
	 * function: rsyncDryRun()
	 *
	 * Command example: rsync --checksum -navz --delete -e "ssh -i /home/user/.ssh/id_rsa -p 22" /from user@example.com:/to
	 * @category: exec
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
	
	/**
	 * function: listingOptions()
	 *
	 * @category: exec
	 */
	public function listingOptions() {
		var_dump($this->optionlist());
		return true;
	}
	
	/**
	 * function: optionlist()
	 *
	 * @category: data
	 */
	public function optionlist() {
		/**
		Options list
		*/
		$this->options = array(
			"--info" => array(
				"description" => "Display all parameters being set.",
				"type" => "Dry run",
			),
			"--dryrun" => array(
				"description" => "Dry run (virtual execution) of the execution of synchronization.",
				"type" => "Dry run",
			),
			"--list" => array(
				"description" => "Display a list of available execution options.",
				"type" => "Dry run",
			),
		);
		return $this->options;
	}
	
	
	
	/**
	 * function: dbsync()
	 *
	 * @category: exec
	 */
	public function dbsync() {
		
		/* mysqldump on localhost */
		
		
		/* restore on remote server */
		$connection = $this->ssh2Connect();
		
		if ($connection !== false) {
			if (ssh2_auth_pubkey_file(
				$connection,
				$this->remoteWebServer["user"],
				$this->remoteWebServer["sshPubKeyPath"],
				$this->remoteWebServer["sshSecletKeyPath"], ''))
			{
				$stream = ssh2_exec($connection, 'ls -al');
				$stream_err = ssh2_fetch_stream($stream,SSH2_STREAM_STDERR);
				stream_set_blocking($stream,true);
				fread($stream_err,4096);
				var_dump(fread($stream,4096));
			}
		}
		
		
		
		return "dbsync";
	}
	
	
	
	/**
	 * function: ssh2Connect()
	 *
	 * @category: abstruct
	 */
	public function ssh2Connect() {
		
		/* Connection test */
		$connection = ssh2_connect(
			$this->remoteWebServer["remoteHost"],
			$this->remoteWebServer["port"]
		);
		
		if ($connection!==false) {
			return $connection;
		} else {
			return false;
		}
		
//		if ($connection!==false) {
//			if (ssh2_auth_pubkey_file(
//				$connection,
//				$this->remoteWebServer["user"],
//				$this->remoteWebServer["sshPubKeyPath"],
//				$this->remoteWebServer["sshSecletKeyPath"], '')
//			)
//			{
//				echo "Public Key Authentication Successful.\n";
//				echo "SSH remote connection parameters are correct.\n";
//				
//				$stream = ssh2_exec($connection, 'ls -al');
//				$stream_err = ssh2_fetch_stream($stream,SSH2_STREAM_STDERR);
//				stream_set_blocking($stream,true);
//				
//				fread($stream_err,4096);
//				var_dump(fread($stream,4096));
//				
//			}
//		} else {
//			die("remote host: ".$this->remoteWebServer["remoteHost"]." or port: ".$this->remoteWebServer["port"]." are maybe wrong. \n");
//		}
		
		
		
	}
	
	
}







<?php
/**
synchronous
config.php
*/

$clientWebServer = array(
	/**
	 * Since rsync is used, it is normally set as SSH.
	 */
	"protocol" => "ssh",
	/**
	 * "clientHost"
	 * Unless there is a problem, this value will not be used in most cases.
	 * It is okay to leave it as it is.
	 * You should provide the information necessary for SSH connection,
	 * but in most cases these values may not be used as it is the connection source.
	 * SSHKEY authentication is recommended.
	 */
	"clientHost" => "localhost",
	"user" => "client_user",
	"password" => "",
	"sshPubKeyPath" => "~/.ssh/sshkey/rsa.pem.pub",
	"sshSecletKeyPath" => "~/.ssh/sshkey/rsa.pem",
	"port" => "22",
	"authenticationMethod" => "sshkey", /* sshkey | password */
	/**
	 * This is the file location information of the web server. These files are synchronized.
	 */
	"userDirectory" => "/home/client_user",
	"documentRoot" => "/home/client_user/public",
	/**
	 * This file sets the files to ignore during synchronization.
	 * This file is described by relative path from the path where rsync is executed.
	 */
	"rsyncIgnore" => "ignore",
	/**
	 * Working directory. Permission 777 is desirable.
	 */
	"clientWorkDir" => "/tmp",
	"synchronousLog" => "/var/log/synchronous.log",
);


$remoteWebServer = array(
	/**
	 * Since rsync is used, it is normally set as SSH.
	 */
	"protocol" => "ssh",
	"remoteHost" => "remote.example.com",
	"user" => "remote_user",
	"password" => "",
	"sshPubKeyPath" => $clientWebServer["sshPubKeyPath"],
	"sshSecletKeyPath" => $clientWebServer["sshSecletKeyPath"],
	"port" => "3949",
	"authenticationMethod" => "sshkey", /* sshkey | password */
	/**
	 * Remote side synchronized target directory.
	 */
//	"userDirectory" => "/home/client_user",
	"userDirectory" => "/tmp",
	"documentRoot" => "/home/client_user/appowner/public",
	/**
	 * This file sets the files to ignore during synchronization.
	 * This file is described by relative path from the path where rsync is executed.
	 */
	"rsyncIgnore" => $clientWebServer["rsyncIgnore"],
	/**
	 * Working directory. Permission 777 is desirable.
	 */
	"remoteWorkDir" => "/tmp",
);



$appInformation = array(
	"title" => "Applications infomation.",
	"appName" => "synchronous",
	"version" => "1.0",
	"description" => "Synchronize web applications between remote hosts.",
	"github" => "https://github.com/osamuya",
	"since" => "2017-12-01",
);





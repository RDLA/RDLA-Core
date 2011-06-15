<?php
/**************************************************
*
*                 RDLA Core V2
*                 Author: Damien BRIAN - damien.brian@rdla.fr
*                 Date: 2010
*                 ---------------------------------
*                 File: Data.class.php
*
***************************************************/
class Data {

private $nbRequest;
private $timer;
private $instance;

public function __construct()
	{
	global $config;
	$db = $config->database();
	$this->instance = new PDO($db['connexionString'], $db['user'], $db['password']);
	}
public function query($requete, array $param = array())
	{
		global $config;
		$starttimer=time()+microtime(); 
		$req = $this->instance->prepare($requete);
		$exec = $req->execute($param);
		if (!$exec)
			{
			$error = "RDLACore: [PDO Error]";
			if($config->debug() == "true")
				{
				$err = $req->errorInfo();
				$error .= ': <strong>'.$requete."</strong> | ".$err[2];
				}
			throw new Exception($error);
			}
		$retour = array();
		while($record=$req->fetch(PDO::FETCH_OBJ))
			$retour[] = $record;
		$this->nbRequest++;
		$this->timer += (time()+microtime())-$starttimer;
		return $retour;
	}	
public function nbRequest()
{
	return $this->nbRequest;
}
public function getTime($round = 4)
{
	return round($this->timer,$round);
}

}
?>
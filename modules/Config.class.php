<?php
/**************************************************
*
*                 RDLA Core V2
*                 Author: Damien BRIAN - damien.brian@rdla.fr
*                 Date: 2010
*                 ---------------------------------
*                 File: Config.class.php
*
***************************************************/
class Config {

private $skin;
private $lang;
private $cache;
private $debug;
private $database;

private $conf;

public function __construct($environment)
	{
		$dom = new DomDocument();
		$dom->load("config/config.xml");
		
		//Load all configuration
		$listConfig = $dom->getElementsByTagName("config");
		foreach($listConfig as $config)
		{
			if($config->getAttribute("id") == $environment)
			{
				$this->conf = $config;
				$this->database["host"] = $config->getElementsByTagName("database")->item(0)->getAttribute("host");
				$this->database["user"] = $config->getElementsByTagName("database")->item(0)->getAttribute("user");
				$this->database["password"] = $config->getElementsByTagName("database")->item(0)->getAttribute("password");
				$this->database["dbname"] = $config->getElementsByTagName("database")->item(0)->getAttribute("dbname");
				$this->database["connexionString"] = $config->getElementsByTagName("database")->item(0)->getAttribute("connexionString");
				break;
			}
		}
	}
public function __get($key) {
         return $this->conf->getElementsByTagName($key)->item(0)->firstChild->nodeValue;
     }

public function database(){
	return $this->database;
}

public function __toString() {
     return "Skin : $this->skin | Lang : $this->lang | Cache : $this->cache | Debug : $this->debug | Database ({$this->database["type"]}): {$this->database["user"]}:{$this->database["password"]}@{$this->database["location"]}/{$this->database["base"]}";
   }


}
?>
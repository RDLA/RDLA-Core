<?php
/***************************************************
*
*                 RDLA Core V2
*                 Author: Damien BRIAN - damien.brian@rdla.fr
*                 Date: 2010
*                 ---------------------------------
*                 File: index.php
*
***************************************************/
//Autoload modules
function __autoload($class) 
{
   require_once 'modules/'.$class.'.class.php';
}
function setCredential($credential)
{
	$_SESSION['RDLACore']['credential'] = $credential;
}
function getCredential()
{
	if(!isset($_SESSION['RDLACore']['credential'] ))
		$_SESSION['RDLACore']['credential']  = "visitor";
	return $_SESSION['RDLACore']['credential'];
}
 
//Load Configuration Environnement
if($_SERVER["SERVER_NAME"] == 'localhost')
{
$env = "dev";
}
else
{
$env = "prod";
}
$config = new Config($env);

//Detect loading module
if (!empty($_GET['p'])) 
$module =  htmlspecialchars($_GET['p']);
else $module = 'index';
// Start session for user credential
session_start();
// Show Module
try
{

	$page = new Template($module);
	
	if($env == "dev")
            $page->appendBody(DevToolBar::show($module));
	
	echo $page->show();
}
catch(Exception $e)
{
	echo "<span style='color:#900;font-weight:bold;'>Error:</span> ".$e->getMessage();
}

?>
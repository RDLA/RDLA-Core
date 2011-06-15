<?php
/**************************************************
*
*                 RDLA Core V2
*                 Author: Damien BRIAN - damien.brian@rdla.fr
*                 Date: 2010
*                 ---------------------------------
*                 File: DevToolBar.class.php
*
***************************************************/
class DevToolBar {

public function __construct() {
  
   }
	public static function show($module = "index")
	{
	global $config;
	return "<div style='position:fixed;bottom:0;left:0;width:100%;background:#900;color:#FFF;'><img src='skin/{$config->skin}/img/favicon.png' style='float:left;margin-right:1.5em;' alt='Cymcaël' /><span style='display:block;margin-top:6px;'> [DevToolBar] Debug - Module: $module - Credential: ".getCredential()."</span></div>";
	}
}

?>
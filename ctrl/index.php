<?php
//Select action to execute
if(isset($_GET['a'])) $action = $_GET['a'];
else $action = "default";


switch($action)
{
	case "default":
	default:
		$this->add("Hello World","TEXTE");
	break;

}

?>
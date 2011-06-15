<?php
/**************************************************
*
*                 RDLA Core V2
*                 Author: Damien BRIAN - damien.brian@rdla.fr
*                 Date: 2010
*                 ---------------------------------
*                 File: Template.class.php
*
***************************************************/
//!  Template class. Process the view.
/*!
  Template class is used for creating and manipulating the view
  of the application. It also load each module, controller and
  extra for a specified bonus
*/
class Template {

private $module; /*!< Module to load */
private $header;/*!< Skin's header */
private $footer;/*!< Skin's footer */
private $content;/*!< Content of Module Selected */
private $mask;/*!< Attribute used for parsing the language */
private $vmask;/*!< Attribute used for parsing the language*/

//! Constructor. prepare the template for selected module
/*!
\param $module the module you want to load
*/
public function __construct($module = 'index') {
   global $config;
   $this->module = $module;
   
   //Module not found
   $error404 = true;
   if($this->hasCredential($module))
   {
	   // Loading View
	   if (@!$this->header = file_get_contents('skin/'.$config->skin.'/header.tpl'))
		  $this->header = '';
	   if (@!$this->footer = file_get_contents('skin/'.$config->skin.'/footer.tpl'))
		  $this->footer = '';
	   if (@!$this->content = file_get_contents('skin/'.$config->skin.'/'.$module.'.tpl'))
		  $this->content = '';
	   else
		  $error404 = false;
	   
	   // Loading Javascript Files
	   if(file_exists('js/'.$module.'.js'))
	   {
		$this->header = str_replace('{HEAD}',"<script type='text/javascript' src='js/".$module.".js'></script>\n{HEAD}",$this->header); 
	   }
	   // Loading controllers
	   if(file_exists('ctrl/'.$module.'.php'))
	   {
		  require_once('ctrl/'.$module.'.php');
		  $error404 = false;
	   }
	   
	   if($error404)
	   {
		throw new Exception("RDLACore: 404 Error");		  
	   }
   }
   else
   {
	throw new Exception("RDLACore: Invalid Credential");

   }
}
//! Check the credential of the user for the specified module
/*!
Check the credential of the user for the specified module. Return a boolean.
\param $module the module you want to check
\return True if the user was authorized, else return false.

*/
public function hasCredential($module)
{
$isAuth = false;
$user_credential = getCredential();
$dom = new DomDocument();
$dom->load("config/credentials.xml");
$userModule = $dom->getElementsByTagName($user_credential)->item(0);
$isAuth = $userModule->getElementsByTagName("rdlaCoreDefault")->item(0)->firstChild->nodeValue;
$getModule = $userModule->getElementsByTagName($module);
foreach($getModule as $mod)
	{
		$isAuth = $mod->firstChild->nodeValue;
	}
if($isAuth == 'false') $isAuth = false;
else if ($isAuth == 'true') $isAuth = true;

return $isAuth;
}


public function add($value,$mask)
{
// Call by controllers, add to an array the value of a template property
   $this->mask[]  = '{'.$mask.'}';
   $this->vmask[] = $value;
}
public function parseLang()
{
	global $config;
	$dom = new DomDocument();
	$dom->load("config/lng_".$config->lang.".xml");
	$listWord = $dom->getElementsByTagName("word");
	foreach($listWord as $word)
		{
			$property = $word->getAttribute("id");
			$value = $word->firstChild->nodeValue;
			//echo "$property => $value<br />";
			$this->header = str_replace('{'.$property.'}',$value,$this->header); 
			$this->content = str_replace('{'.$property.'}',$value,$this->content); 
			$this->footer = str_replace('{'.$property.'}',$value,$this->footer); 
		}
	$this->header = str_replace('{HEAD}',"",$this->header); 
	$this->header = str_replace('{SKIN_DIR}',"skin/".$config->skin,$this->header); 
}
public function parseModule()
{
	//Replace all the property by the value defined in the array
	$this->header = str_replace($this->mask,$this->vmask,$this->header); 
	$this->content = str_replace($this->mask,$this->vmask,$this->content); 
	$this->footer = str_replace($this->mask,$this->vmask,$this->footer); 
}
public function appendBody($value)
{
$this->footer = str_replace("</body>",$value."</body>",$this->footer); 
}
public function show()
{ //Return the final HTML Code
	$this->parseLang();
	$this->parseModule();
	return $this->header.$this->content.$this->footer;
}


}
?>
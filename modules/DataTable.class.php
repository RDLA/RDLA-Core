<?php
/**************************************************
*
*                 RDLA Core V2
*                 Author: Damien BRIAN - damien.brian@rdla.fr
*                 Date: 2010
*                 ---------------------------------
*                 File: DataTable.class.php
*
***************************************************/
abstract class DataTable {

protected $attributes = array();
protected $id;
protected $instance;
protected $table;
protected $mode;

public function __construct( array $id,$donnees = "*")
	{
		$this->instance = new Data();
		$this->table = get_class($this);
		if(empty($id))
		{
		$this->mode = "write";
		}
		else
		{
		$this->mode = "read";
		$this->id=$id;		
		$id = $this->getID();
		$requete = "SELECT $donnees FROM $this->table ";
		if(count($id) != 0)
			$requete .=  "WHERE $id";
		
		$data = $this->instance->query($requete,$this->attributes);
		if(isset($data[0]))
		{
		
			foreach($data[0] as $key=>$value)
			{
			$this->attributes[$key] = $value;
			}
		}
		else if(!empty($id))
		{
			$error = "RDLACore: [DataTable Error]";
			global $config;
			if($config->debug == "true")
				{
				$error .= " \"Wrong Parameter [$id] for $this->table\"";
				}
			throw new Exception($error);
		}
		}

	}
public function __get($key) {
         return $this->attributes[$key];
     }
	 
public function __set($key,$value) {
         $this->attributes[$key] = $value;
     }
public function getInstance()
{
	return new Data();
}
public function save()
	{
	if($this->mode == "read")
		{//UPDATE
			$output ="";
			foreach($this->attributes as $key=>$value)
				{
				$output .= "$key=:$key,";
				}
			$output = substr($output, 0, -1);
			$id = $this->getID();
			$this->instance->query("UPDATE $this->table SET $output WHERE $id",$this->attributes);
		}
	else
		{//INSERT
			$param = "";
			$val = "";
			foreach($this->attributes as $key=>$value)
				{
				$param .= "$key,";
				$val .= ":$key,";
				}
			$param = substr($param, 0, -1);
			$val = substr($val, 0, -1);
			$this->instance->query("INSERT INTO $this->table ($param) VALUES($val)",$this->attributes);
		}
	}
public function getID()
{
$id = "";
	foreach($this->id as $key=>$value)
		{
		$id .= "$key='$value' AND";
		}
		$id = substr($id, 0, -4);
return $id;
}

public function delete()
	{
		$id = $this->getID();
		$this->instance->query("DELETE FROM $this->table WHERE $id");
	}
public function __toString() {
if(isset($this->id)) $id = $this->getID();
$output ="[$this->table]";
	foreach($this->attributes as $key=>$value)
		{
		$output .= "$key=$value,";
		}
	$output = substr($output, 0, -1);
	return $output;
}

}
?>
<?php
class Starter
{
    public $Config = NULL;
    function __construct($configfile = "config.json"){
//get the config file
if(!file_exists($configfile))exit("Eduporta Not Configured");
$config = file_get_contents($configfile);
$config = $config = substr($config,strpos($config,"{"));
$config = json_decode($config,true);
if(is_null($config) || !isset($config['Version']))exit("Invalid Config File");
$this->Config = $config;
    }
    public function StartPortal(){
        include($this->Config['Core']."index.php");
    }
    public function StartCPortal(){
        include($this->Config['Core']."cportal/index.php");
    }
}
if(isset($configfile) && trim($configfile) != ""){
    $Starter = new Starter($configfile);
}else{
    $Starter = new Starter();
}



?>
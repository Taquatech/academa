<?php
$configfile = "../../config.json";
include("../../starter.php");
$Core = $Starter->Config['Core'];
$SubDir = $Starter->Config['SubDir'];
$configdir = "../../";
require_once("../../".$Core."general/config.php"); 
//include("../../epconfig/TaquaLB/Ajax/CGI/PHP/config.php"); //the configuration object (connection parameters)
include("../../".$Core."general/getinfo.php");//hold basic functions to perform some common database operations or request
//PAYEE_ID=XXXX&PAYMENT_TYPE=XXXXGetPayInfo
if(isset($_REQUEST['PAYEE_ID']) ){
$payeeID = $_REQUEST['PAYEE_ID'];
$payType = @$_REQUEST['PAYMENT_TYPE'];


echo GetPayInfo($payeeID,$payType);

}


?>
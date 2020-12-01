<?php
$configfile = "../../config.json";
include("../../starter.php");
$Core = $Starter->Config['Core'];
$SubDir = $Starter->Config['SubDir'];
$configdir = "../../";
require_once("../../".$Core."general/config.php");  //the configuration object (connection parameters)
include("../../".$Core."general/getinfo.php");//hold basic functions to perform some common database operations or request

function LogError($descr="",$msg=""){
	if(trim($descr) == "" && trim($msg) == "")return;
	global $dbo;
	 $inst = $dbo->InsertID("paymentlog_tb",["Description"=>$descr,"Message"=>$msg,"Type"=>"ETRANZACT_DUMP"]);
	 exit($msg);
}

$ip = $_SERVER['HTTP_CLIENT_IP']?$_SERVER['HTTP_CLIENT_IP']:($_SERVER['HTTP_X_FORWARDE‌​D_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']);
$local = "127.0.0.1";
$demo = "197.255.244.10";$live = "197.255.244.5";
$ip = trim($ip);
if($ip == $demo){
    if(!isset($_REQUEST['RECEIPT_NO']) || !isset($_REQUEST['TRANS_AMOUNT']) || !isset($_REQUEST['TRANS_DATE']) || !isset($_REQUEST['BANK_NAME']) || !isset($_REQUEST['BRANCH_NAME']) || empty($_REQUEST['RECEIPT_NO']) || empty($_REQUEST['TRANS_AMOUNT'])){
		$_REQUEST['IP'] = $ip;
		LogError(json_encode($_REQUEST),"Transaction Status = false 3");
       // exit("Transaction Status = false 3");
        //exit;
    }
  $payeeID = $dbo->SqlSafe($_REQUEST['CUSTOMER_ID']);
  $amt = (float)$_REQUEST['TRANS_AMOUNT'];
  $trans_Date = $_REQUEST['TRANS_DATE'];
  $bank = $_REQUEST['BANK_NAME'];
  $bankBranch = $_REQUEST['BRANCH_NAME'];
 $datearr = explode(" ",$trans_Date);
 $dateonly = $datearr[0];
 $dateonlyarr = explode("/",$dateonly);
 $dateonlyarr = array_reverse($dateonlyarr);
 $datemysql = implode("-",$dateonlyarr);
 $payhist = $dbo->SelectFirstRow("payhistory_tb","","TransID = '$payeeID' limit 1",MYSQLI_ASSOC);
 if(is_array($payhist)){
	$_REQUEST['IP'] = $ip;
	LogError(json_encode($_REQUEST),"Transaction Status = false 1");
//exit("Transaction Status = false 1");
 }
 //get the order details using the payyeeID
 $orderdet = $dbo->SelectFirstRow("order_tb","","TransNum = '$payeeID' limit 1",MYSQLI_ASSOC);
								if(!is_array($orderdet)){//order not found
									$_REQUEST['IP'] = $ip;
									LogError(json_encode($_REQUEST),"Transaction Status = false -1");
								}else{
                                    $amtord = (float)$orderdet['Amt'];
                                    if($amt != $amtord){
										$_REQUEST['IP'] = $ip;
									LogError(json_encode($_REQUEST),"Transaction Status = false 4");
									}
									$inst = MakePaidByRef($payeeID,$orderdet,["Amt"=>$amtord,"Bank"=>$bank,"Branch"=>$bankBranch,"DateTime"=>date('Y-m-d')]);
									if($paid[0] == 1){
										$_REQUEST['IP'] = $ip;
									LogError(json_encode($_REQUEST),"Transaction Status = true");
									
									}else{
										$_REQUEST['IP'] = $ip;
										LogError(json_encode($_REQUEST),"Transaction Status = false -2");
										//exit('Transaction Status = false -2');
									}
                                   /* $dbo->Bigin();
								   $inst = $dbo->Insert2DbTb(array(
									                      "RegNo"=>$orderdet['RegNo'],
														  'Lvl'=>$orderdet['Lvl'],
														  'Sem'=>$orderdet['Sem'],
														  'Ses'=>$orderdet['Ses'],
														  'Amt'=>$orderdet['Amt'],
														  'TransID'=>$orderdet['TransNum'],
														  'PayID'=>$orderdet['ItemID'],
														  'PayBrkDn'=>$orderdet['BrkDwn'],
														  'itemNum'=>$orderdet['ItemNo'],
														  'PayDate'=>$datemysql,
                                                          'Bank'=>$bank,
													'BnkBranch'=>$bankBranch,
													'Info'=>StudPatchDet($orderdet['RegNo'],$orderdet['ItemID']),
													'TransAmt'=>$orderdet['Amt']
														  ),"payhistory_tb");
								 //Updatedbtb($tb,$fieldsValeus,$cond = ""){
								 $updt = $dbo->Updatedbtb("order_tb",array('Paid'=>1),"TransNum = '$payeeID'");
								 if($inst == "#" && is_array($updt)){
									 $dbo->Commit();
                                     exit("Transaction Status = true");
								 }else{

									 $dbo->Rollback();
									 exit('Transaction Status = false -2');
								 } */
								}
}else{
	$_REQUEST['IP'] = $ip;
	LogError(json_encode($_REQUEST),"Transaction Status = false");
    //echo "Transaction Status = false"; //wrong ip 
}

//confirm if order found

//echo $ip;
?>
<?php
error_reporting(0);
/* include("../../../TaquaLB/Ajax/CGI/PHP/config.php"); //the configuration object (connection parameters)
include("../../PHP/getinfo.php");//hold basic functions to perform some common database operations or request*/
 //the caller or script that include it should also include the config and getinfo
if(isset($_GET['regno'])){
	$regno = $_GET['regno'];
	$studReg = STUDREG(); 
	$binfo = GetBasicInfo($regno,"sch","p");
	//print_r($binfo);
	//$ac = GetBasicInfo($regno,"ac",@$studReg['StudInfoPref']);
	
}
$pdf->Banner("Registration Comfirmation Slip",array("LogoSize"=>"80px*80px","WaterMark"=>"Abbr"));
  if(!is_array($binfo)){
	      if($binfo == "0"){
          $pdf->HTML();
	  ?>
                 <div class="errl">Candidate Not Found</div>
                <?php
               //exit;
            }else{
	  ?>
       
      <div class="errl">Internal Error</div>
      </div>
      </body>
      <?php
      $pdf->_HTML();
      $pdf->Finish();
            }
	 exit;
  }
//echo $binfo['OtherDet'];
  $otherdet = json_decode($binfo['OtherDet'],true);
  $binfo = array_merge($binfo,$otherdet);
  $pdf->Panel();
    $pdf->InfoBox(2.5);
      $pdf->InfoTitle("BASIC DETAILS");
      $pdf->Info("SURNAME:",strtoupper(@$binfo['SurName']));
      $pdf->Info("FIRSTNAME:",strtoupper(@$binfo['FirstName']));
      $pdf->Info("OTHERNAMES:",strtoupper(@$binfo['OtherNames']));
      $pdf->Info("FORMER:",strtoupper(@$binfo['FormerName']));
     
      $db = new DateTime($binfo['DOB']); $dtb = $db->format("d, M Y");
      $pdf->Info("DATE OF BIRTH:",$dtb);
      $pdf->Info("NATIONALITY:",strtoupper(@$binfo['Nationality']));
      $pdf->Info("STATE OF ORIGIN:",strtoupper(@$binfo['StateName']));
      $pdf->Info("LGA:",strtoupper(GetLGA(@$binfo['LGA'])));
      $pdf->Info("GENDER:",substr(strtoupper(@$binfo['Gender']),0,1) == "M"?"MALE":"FEMALE");
      $pdf->Info("MARITAL STATUS:",(trim(@$binfo['MaritalStatus']) == "M")?"MARRIED":"SINGLE");
    $pdf->_InfoBox();
    //passport
    $pdf->InfoBox(1.5);
    $pdf->InfoTitle("PASSPORT PHOTOGRAPH");
    $pdf->Dump("<div style=\"margin:auto;margin-top:0px;margin-bottom:5px;width:180px;height:180px\">");
     $pdf->Image($pdf->BaseConfigPath.str_replace("../epconfig","",trim($binfo['Passport'])),"width:100%;height:100%;text-align:center");
     $pdf->Dump("</div>");
    $pdf->_InfoBox();

  $pdf->_Panel();

  $pdf->Panel();
  //acedemic
   $pdf->InfoBox(2);
      $pdf->InfoTitle("ACEDEMIC DETAILS");
      //  $pdf->Info("REGISTRATION NO.:",@$regno);
      $pdf->Info("FACULTY/SCHOOL:",@$binfo['FacName']);
      // $pdf->Info("DEPARTMENT:",strtoupper(@$binfo['DeptName']));
      $pdf->Info("DEPARTMENT:",@$binfo['ProgName']);
      //get the degree
      $deg = $dbo->SelectFirstRow("school_degrees_tb","","ID=".(int)$binfo['Degree']);
      $binfo['Degree'] = is_array($deg)?$deg['Name']:'';
      $pdf->Info("DIPLOMA/DEGREE:",$binfo['Degree']);
      $pdf->Info("AREA OF SPECIALIZATION:",@$binfo['AreaofSpecial']);
      $pdf->Info("STUDY MODE:",(int)@$binfo['StudyModeFullTime'] < 1?'PART TIME':'FULL TIME');
      $pdf->Info("RESEARCH TOPIC:",@$binfo['ResearchTopic']);
    //  $pdf->Info("ACCESS CODE:",@$binfo['AccessCode'] );
   $pdf->_InfoBox();
   //contact
  $pdf->InfoBox(2);
    $pdf->InfoTitle("CONTACT DETAILS");
    $pdf->Info("PHONE NUMBER:",@$binfo['Phone']);
    $pdf->Info("EMAIL:",@$binfo['Email']);
    $pdf->Info("HOME ADDRESS:",@$binfo['Addrs']);
    $pdf->Info("MAILING ADDRESS:",@$binfo['OtherAddress']);
    // $pdf->Info("NOK PHONE:",@$binfo['Nphone']);
  $pdf->_InfoBox();
  
  $pdf->_Panel();

  $pdf->Panel();
  $pdf->InfoBox(4);
  $pdf->InfoTitle("EDUCATIONAL QUALIFICATION");
  $pdf->Info("a: Secondary Education","");
  $pdf->Table("margin-bottom:10px");
      $pdf->TableHead(array("Qualification","Date Awarded","Awarding Institution"));
      //get the secondary qualification
      foreach($binfo['SecEdu'] as $secdet){
        if(trim($secdet['col1']) != "" || trim($secdet['col2']) != "" || trim($secdet['col3']) != ""){
          $pdf->TableRow(array($secdet['col1'],$secdet['col2'],$secdet['col3']));
        }
        
      }

      $pdf->_Table();
      $pdf->Info("b: Tertiary Education","");
      $pdf->Table("margin-bottom:10px");
          $pdf->TableHead(array("Qualification","Date Awarded","Awarding Institution"));
          //get the secondary qualification
          foreach($binfo['TerEdu'] as $secdet){
            if(trim($secdet['col1']) != "" || trim($secdet['col2']) != "" || trim($secdet['col3']) != ""){
              $pdf->TableRow(array($secdet['col1'],$secdet['col2'],$secdet['col3']));
            }
            
          }
    
          $pdf->_Table();
          $pdf->Info("c: University Education","");
          $pdf->Table("margin-bottom:10px");
              $pdf->TableHead(array("Qualification","Date Awarded","Awarding Institution"));
              //get the secondary qualification
              foreach($binfo['UniEdu'] as $secdet){
                if(trim($secdet['col1']) != "" || trim($secdet['col2']) != "" || trim($secdet['col3']) != ""){
                  $pdf->TableRow(array($secdet['col1'],$secdet['col2'],$secdet['col3']));
                }
                
              }
        
              $pdf->_Table();

  $pdf->_InfoBox();   
  $pdf->_Panel();
  $pdf->Panel();
  $pdf->InfoBox(4);
  $pdf->InfoTitle("REFEREES");
  $pdf->HTML();
  echo '<div style="text-align:left;padding:15px">At least one should have taught you at tertiary education level</div>';
  $pdf->_HTML();
  $pdf->Table("margin-bottom:10px");
              $pdf->TableHead(array("Name","Phone","E-mail Address"));
              //get the secondary qualification
              foreach($binfo['Referee'] as $secdet){
                if(trim($secdet['col1']) != "" || trim($secdet['col2']) != "" || trim($secdet['col3']) != ""){
                  $pdf->TableRow(array($secdet['col1'],$secdet['col2'],$secdet['col3']));
                }
                
              }
        
              $pdf->_Table();
  $pdf->_InfoBox(); 
  $pdf->_Panel();
  $pdf->HTML();
  echo '<ul style="text-align:left;padding:15px">
  <li>Request the appropriate authority of your University to forward your Transcript or a Statement of your academic records for the period of your stay at that University directly to this University.</li>
  </ul>';
  $pdf->_HTML();
  
  $pdf->FooterNote("Registration Comfirmation Slip - ".@$regno);

  $pdf->Finish();
  //exit();
?>
  
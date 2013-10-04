<?php
/*
=======================================================================================
Copyright: Electronic and Mobile Commerce Software Ltd
Product: TradingEye
Version: 7.0.5
=======================================================================================
*/

ini_set('display_errors', "1");
error_reporting(E_ERROR);

include_once("../plugins_common.php");
include_once(SITE_PATH."libs/csv.php");


# QuickBooks 2008 

class c_QuickBooksCsv extends c_commonPlugin{
	
	#GLOBLE VARIABLES
	var $startDate;
	var $endDate;
	var $startDate_order;
	var $endDate_order;
	var $acc_ref;
	var $csv;
	
	
	function __construct(){
          $this->c_QuickBooksCsv();
     }
     
     
    function c_QuickBooksCsv(){ #Constructor
		$this->c_commonPlugin();
		$this->columnSeperator			=	"\",\"";
		$this->lineSeperator			=	"\r\n";
		$this->emptyValue				=	"";
		$this->libFunc					=	new c_libFunctions();	
		$this->csv						= 	new export2CSV();
		$this->csv->delimiter			=	",";
		
		$mode=$this->libFunc->ifSet($_REQUEST,'csvtype',"");		
		
		#HANLING SELECTED DATE
		if($this->libFunc->ifSet($_REQUEST,'start_date',"") && !$this->libFunc->m_isNull($_REQUEST['start_date'])){
			$arrStartDate=explode("/",$_REQUEST['start_date']);
			$arrStartDate[1]=$this->libFunc->ifSet($arrStartDate,1);
			$arrStartDate[0]=$this->libFunc->ifSet($arrStartDate,0);
			$arrStartDate[2]=$this->libFunc->ifSet($arrStartDate,2);
			$this->startDate=mktime(0,0,0,$arrStartDate[1],$arrStartDate[0],$arrStartDate[2]);
		}
		if($this->libFunc->ifSet($_REQUEST,'end_date',"") && !$this->libFunc->m_isNull($_REQUEST['end_date'])){
			$arrStartDate=explode("/",$_REQUEST['end_date']);
			$arrStartDate[1]=$this->libFunc->ifSet($arrStartDate,1);
			$arrStartDate[0]=$this->libFunc->ifSet($arrStartDate,0);
			$arrStartDate[2]=$this->libFunc->ifSet($arrStartDate,2);	
		  	$this->endDate=mktime(23,59,59,$arrStartDate[1],$arrStartDate[0],$arrStartDate[2]);
		}
		if($this->libFunc->ifSet($_REQUEST,'start_date_order',"") && !$this->libFunc->m_isNull($_REQUEST['start_date_order'])){
			$arrStartDate=explode("/",$_REQUEST['start_date_order']);
			$arrStartDate[1]=$this->libFunc->ifSet($arrStartDate,1);
			$arrStartDate[0]=$this->libFunc->ifSet($arrStartDate,0);
			$arrStartDate[2]=$this->libFunc->ifSet($arrStartDate,2);
			$this->startDate_order=mktime(0,0,0,$arrStartDate[1],$arrStartDate[0],$arrStartDate[2]);
		}
		if($this->libFunc->ifSet($_REQUEST,'end_date_order',"") && !$this->libFunc->m_isNull($_REQUEST['end_date_order'])){
			$arrStartDate=explode("/",$_REQUEST['end_date_order']);
			$arrStartDate[1]=$this->libFunc->ifSet($arrStartDate,1);
			$arrStartDate[0]=$this->libFunc->ifSet($arrStartDate,0);
			$arrStartDate[2]=$this->libFunc->ifSet($arrStartDate,2);	
			$this->endDate_order=mktime(23,59,59,$arrStartDate[1],$arrStartDate[0],$arrStartDate[2]);
		}
		

		switch($mode){
			case "user":				
				$this->m_generateCsvCustomer();				
			break;
			case "supplier":				
				$this->m_generateSupplier();			
			break;
			case "help":
				$this->m_displayHelpFile();
			break;
			default:
				
				$this->m_setTempate();
				if($this->m_checkStatus()){
					$this->m_defaultView();
				}		
				$this->m_parseTemplate();
				
			break;
		} //end of switch
    } #End of constructor



	#FUNCTION TO DISPLAY TEMPLATE TO DISPLAY LINKS
	function m_defaultView($errMsg=0){
		$this->obMainTemplate->set_file('hTemplate','QuickBooks_csv.tpl.htm');
		
		$this->obMainTemplate->set_block('hTemplate','ERRMSG_CUS_BLK','hERRMSG_CUS_BLK');
		
		
		$this->obMainTemplate->set_var("hERRMSG_CUS_BLK","");
		$this->obMainTemplate->set_var("TPL_VAR_FORM_NO","");
		
		$this->obMainTemplate->set_var('TPL_VAR_SITEURL',SITE_URL);
		if($errMsg){
		  
		  $this->obMainTemplate->set_var('TPL_CUS_ERRMSG',$errMsg);
		  
		
		  	$this->obMainTemplate->set_var("TPL_VAR_FORM_NO","customer");
		  	$this->obMainTemplate->parse('hERRMSG_CUS_BLK','ERRMSG_CUS_BLK',true);
		  
		  
		}else{
			$this->obMainTemplate->set_var("hERRMSG_CUS_BLK","");
			
		}
		
		$this->obMainTemplate->set_var('TPL_VAR_SITENAME',SITE_NAME);
		
		
		$this->obMainTemplate->set_var('GRAPHICSMAINPATH',SITE_URL."graphics/");
		$this->obMainTemplate->set_var("TPL_VAR_BODY",$this->obMainTemplate->parse('output', 'hTemplate'));

	} #End of defalut view


	#FUNCTION TO GENERATE CSV for customer/supplier	
	function m_generateCsvCustomer(){
		
		$this->err=0;
			
		#QUERY RETRIEVE INFORMATION FOR CUSTOMER TABLE
		$this->obDb->query  = " SELECT distinct '' as accountRef,vFirstName,vLastName,iCustmerid_PK,vEmail,vCompany,
							    SUBSTRING(concat(vFirstName,' ',vLastName),1,60) as Name,							    
							    SUBSTRING(vAddress1,1,60) as vAddress1,
							    SUBSTRING(vAddress2,1,60) as vAddress2,
							    SUBSTRING(vCity,1,60) as vCity,
							    SUBSTRING(if(vState,vState,vStateName),1,60) as stateName,
							    SUBSTRING(vZip,1,60) as vZip,
							   	SUBSTRING(concat(vFirstName,' ',vLastName),1,60) as contactName,
							   	SUBSTRING(vPhone,1,30) as vPhone,
								''	  as CompanyName,
								''	  as AccountNumber FROM ".CUSTOMERS." WHERE iStatus = 1";
					    
		if(isset($this->startDate) && $this->startDate>0)
		{
			$this->obDb->query.=" AND tmSignupDate >='".$this->startDate."'";
		}
		else
		{
			$this->err=1;
			$this->errMsg="<strong>From date</strong> is invalid or empty <br />";
		}
		if(isset($this->endDate) && $this->endDate>0)
		{
			$this->obDb->query.=" AND tmSignupDate <='".$this->endDate."'";
		}
		else
		{
			$this->err=1;
			$this->errMsg.="<strong>To date</strong> is invalid or empty <br /> ";;
		}
		
		$rowCustomer = $this->obDb->fetchQuery();	
		$recordCount = $this->obDb->record_count;
	
		
		$csv_output ="";
		$csv_output ="JOB OR CUSTOMER NAME,COMPANY NAME,";
		$csv_output.="FIRST NAME,LAST NAME,CONTACT,PHONE,EMAIL,";
		$csv_output.="BILLING ADDRESS 1,BILLING ADDRESS 2,BILLING ADDRESS 3,BILLING ADDRESS 4,BILLING ADDRESS 5,";
		$csv_output.="ACCOUNT NUMBER";
		$csv_output .="\n";

		
		
		if($this->err==0)
		{						
			if($recordCount>0)
				{	
					
										
					for($i=0;$i<$recordCount;$i++)
					{										
						# QUERY TO GET STATE
						$this->obDb->query = "SELECT vStateName FROM ".STATES." WHERE iStateId_PK = '".$rowCustomer[$i]->stateName."'";						
						$stateRow = $this->obDb->fetchQuery();	
						$stateRowCt = $this->obDb->record_count;
						
						if ($stateRowCt >0){
						$rowCustomer[$i]->stateName   = $stateRow[0]->vStateName;
						} else {
						$rowCustomer[$i]->stateName   = "";
						}											
						
						$rowCustomer[$i]->accountRef  =  strtoupper(substr($rowCustomer[$i]->vLastName,0,3)).$rowCustomer[$i]->iCustmerid_PK;
						
						$csv_output .= 	$rowCustomer[$i]->Name.',';
						$csv_output .= 	$rowCustomer[$i]->vCompany.',';
						$csv_output .= 	$rowCustomer[$i]->vFirstName.',';
						$csv_output .= 	$rowCustomer[$i]->vLastName.',';
						$csv_output .= 	$rowCustomer[$i]->Name.',';
						$csv_output .= 	$rowCustomer[$i]->vPhone.',';
						$csv_output .=  $rowCustomer[$i]->vEmail.',';
						$csv_output .= 	$rowCustomer[$i]->vAddress1.',';
						$csv_output .=  $rowCustomer[$i]->vAddress2.',';
						$csv_output .= 	$rowCustomer[$i]->vCity.',';
						$csv_output .= 	$stateRow[0]->vStateName.',';
						$csv_output .=  $rowCustomer[$i]->vZip.',';
						$csv_output .= 	$rowCustomer[$i]->accountRef.',';
						$csv_output .=  " \n";									    
					}						
					header( "Content-Type: application/save-as" );
					header( 'Content-Disposition: attachment; filename=customers.csv');
				    echo $csv_output;
				    exit;  				
				}		
				else {
					$errMsg=1;
					$this->m_setTempate();
					$this->errMsg="Sorry, no customer found in selected date range! <br>";
					if($this->m_checkStatus()){
						$this->m_defaultView($this->errMsg);
					}
					$this->m_parseTemplate();
				}		
		}
		else
		{
				
				$errMsg=1;
				$this->m_setTempate();
				if($this->m_checkStatus()){
					$this->m_defaultView($this->errMsg);
				}
				$this->m_parseTemplate();
		} 		
	
	}# End of m_generateCsvCustomer()
	
	#FUNCTION TO GENERATE CSV for supplier	
	function m_generateSupplier(){
		
		$this->err=0;
			
		#QUERY RETRIEVE INFORMATION FOR CUSTOMER TABLE
		$this->obDb->query  = " SELECT distinct '' as accountRef, iVendorid_PK,
							    vCompany as Name,							    
							    vAddress1,
							    vAddress2,
							    SUBSTRING(vCity,1,60) as vCity,
							    vState as stateName,
							    vZip,
							   	vContact as contactName,
							   	vEmail,
							   	vPhone FROM ".SUPPLIERS." WHERE iStatus = 1";
					    
		if(isset($this->startDate) && $this->startDate>0)
		{
			$this->obDb->query.=" AND tmBuildDate >='".$this->startDate."'";
		}
		else
		{
			$this->err=1;
			$this->errMsg="<strong>From date</strong> is invalid or empty <br />";
		}
		if(isset($this->endDate) && $this->endDate>0)
		{
			$this->obDb->query.=" AND tmBuildDate <='".$this->endDate."'";
		}
		else
		{
			$this->err=1;
			$this->errMsg.="<strong>To date</strong> is invalid or empty <br /> ";;
		}

		
		$rowSupplier = $this->obDb->fetchQuery();	
		$recordCount = $this->obDb->record_count;
		
		$csv_output ="";
		$csv_output ="NAME,COMPANY NAME,";
		$csv_output.="ADDRESS 1,ADDRESS 2,ADDRESS 3,ADDRESS 4,ADDRESS 5,";
		$csv_output.="CONTACT,PHONE,EMAIL,ACCOUNT REFERENCE";
		$csv_output .="\n";
		
				
		if($this->err==0)
		{			
			if($recordCount>0)
				{	
								
					for($i=0;$i<$recordCount;$i++)
					{										
						# QUERY TO GET STATE
						$this->obDb->query = "SELECT vStateName FROM ".STATES." WHERE iStateId_PK = ".$rowSupplier[$i]->stateName;
						$stateRow = $this->obDb->fetchQuery();												
						$rowSupplier[$i]->stateName   = $stateRow[0]->vStateName;
						$rowSupplier[$i]->accountRef  =  strtoupper(substr($rowSupplier[$i]->Name,0,3)).$rowSupplier[$i]->iVendorid_PK;
							
					    $csv_output .= 	'"'.$rowSupplier[$i]->Name.'",'; 
						$csv_output .= 	'"'.$rowSupplier[$i]->Name.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->vAddress1.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->vAddress2.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->vCity.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->stateName.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->vZip.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->contactName.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->vPhone.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->vEmail.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->accountRef.'",';	
						$csv_output .=  " \n";
																    
					}							
					header( "Content-Type: application/save-as" );
					header( 'Content-Disposition: attachment; filename=suppliers.csv');
				    print $csv_output;
				    exit;  				
				}		
				else {
					$errMsg=1;
					$this->m_setTempate();
					$this->errMsg="Sorry, no supplier found in selected date range! <br>";
					if($this->m_checkStatus()){
						$this->m_defaultView($this->errMsg);
					}
					$this->m_parseTemplate();
				}		
		}
		else
		{
				
				$errMsg=1;
				$this->m_setTempate();
				if($this->m_checkStatus()){
					$this->m_defaultView($this->errMsg);
				}
				$this->m_parseTemplate();
		} 		
	
	}# End of m_generateCsvSupplier() 
	 
	 
	 
}
$obCsv=new c_QuickBooksCsv();
?>
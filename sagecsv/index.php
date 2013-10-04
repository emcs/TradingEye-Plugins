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


# SAGE 2008 ACCOUNT 

class c_sageCsv extends c_commonPlugin{
	
	#GLOBLE VARIABLES
	var $startDate;
	var $endDate;
	var $startDate_order;
	var $endDate_order;
	var $acc_ref;
	var $csv;
	
	
	function __construct(){
          $this->c_sageCsv();
     }
     
     
    function c_sageCsv(){ #Constructor
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
		
		/*
		if($this->libFunc->ifSet($_REQUEST,'acc_ref',"") && !$this->libFunc->m_isNull($_REQUEST['acc_ref'])){
			$this->acc_ref = $_REQUEST['acc_ref'];
		}else{
			$this->acc_ref = "";
		}
		*/

		switch($mode){
			case "user":				
				$this->m_generateCsvCustomer();				
			break;
			case "supplier":				
				$this->m_generateSupplier();			
			break;
			case "audit":
				$this->m_generateCsvAudit();
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
		$this->obMainTemplate->set_file('hTemplate','sage_csv.tpl.htm');
		$this->obMainTemplate->set_block('hTemplate','ERRMSG_AUDIT_BLK','hERRMSG_AUDIT_BLK');
		$this->obMainTemplate->set_block('hTemplate','ERRMSG_CUS_BLK','hERRMSG_CUS_BLK');
		
		$this->obMainTemplate->set_var("hERRMSG_AUDIT_BLK","");
		$this->obMainTemplate->set_var("hERRMSG_CUS_BLK","");
		$this->obMainTemplate->set_var("TPL_VAR_FORM_NO","");
		
		$this->obMainTemplate->set_var('TPL_VAR_SITEURL',SITE_URL);
		if($errMsg){
		  
		  $this->obMainTemplate->set_var('TPL_CUS_ERRMSG',$errMsg);
		  $this->obMainTemplate->set_var('TPL_AUDIT_ERRMSG',$errMsg);
		  
		  if ($this->libFunc->ifSet($_REQUEST,'csvtype',"")=="audit")
		  {
		  	$this->obMainTemplate->set_var("TPL_VAR_FORM_NO","transaction");
		  	$this->obMainTemplate->parse('hERRMSG_AUDIT_BLK','ERRMSG_AUDIT_BLK',true);	
		  }
		  else
		  {
		  	$this->obMainTemplate->set_var("TPL_VAR_FORM_NO","customer");
		  	$this->obMainTemplate->parse('hERRMSG_CUS_BLK','ERRMSG_CUS_BLK',true);
		  }
		  
		}else{
			$this->obMainTemplate->set_var("hERRMSG_CUS_BLK","");
			$this->obMainTemplate->set_var("hERRMSG_AUDIT_BLK","");
		}
		
		$this->obMainTemplate->set_var('TPL_VAR_SITENAME',SITE_NAME);
		$this->obMainTemplate->set_var('TPL_VAR_NOMINAL_CODE',SAGE_NOMINAL_CODE);
		$this->obMainTemplate->set_var('TPL_VAR_POST_RECEIPTS_TO',SAGE_POST_RECEIPTS_TO);
		
		$this->obMainTemplate->set_var('GRAPHICSMAINPATH',SITE_URL."graphics/");
		$this->obMainTemplate->set_var("TPL_VAR_BODY",$this->obMainTemplate->parse('output', 'hTemplate'));

	} #End of defalut view




	#FUNCTION TO GENERATE CSV for customer/supplier	
	function m_generateCsvCustomer(){
		
		$this->err=0;
			
		#QUERY RETRIEVE INFORMATION FOR CUSTOMER TABLE
		$this->obDb->query  = " SELECT distinct '' as accountRef, vLastName,iCustmerid_PK,
							    SUBSTRING(concat(vFirstName,' ',vLastName),1,60) as Name,							    
							    SUBSTRING(vAddress1,1,60) as vAddress1,
							    SUBSTRING(vAddress2,1,60) as vAddress2,
							    SUBSTRING(vCity,1,60) as vCity,
							    SUBSTRING(if(vState,vState,vStateName),1,60) as stateName,
							    SUBSTRING(vZip,1,60) as vZip,
							   	SUBSTRING(concat(vFirstName,' ',vLastName),1,60) as contactName,
							   	SUBSTRING(vPhone,1,30) as vPhone,
								''    as fax,
								''    as analysis1,
								''    as analysis2,
								''    as analysic3,
								0     as departmentNo,
								''    as vatRegistrationNo,
								0.00  as turnoverMTD,
								0.00  as turnoverYID,
								0.00  as priorYID,
								0.00  as creditLimit,
								'' 	  as terms,
								0     as settlementDueDays,
								0.00  as settlementDiscountRate,
								'4000' as nominalCode,
								'T1'  as textCode FROM ".CUSTOMERS." WHERE iStatus = 1";
					    
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
	
		if($this->err==0)
		{						
			if($recordCount>0)
				{	
					$csv_output ="";
										
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
						
						$rowCustomer[$i]->nominalCode = SAGE_NOMINAL_CODE;
						
														
						$csv_output .= 	'"'.$rowCustomer[$i]->accountRef.'",';		 
						$csv_output .= 	'"'.$rowCustomer[$i]->Name.'",';
						$csv_output .= 	'"'.$rowCustomer[$i]->vAddress1.'",';
						$csv_output .= 	'"'.$rowCustomer[$i]->vAddress2.'",';
						$csv_output .= 	'"'.$rowCustomer[$i]->vCity.'",';
						$csv_output .= 	'"'.$rowCustomer[$i]->stateName.'",';
						$csv_output .= 	'"'.$rowCustomer[$i]->vZip.'",';
						$csv_output .= 	'"'.$rowCustomer[$i]->contactName.'",';
						$csv_output .= 	'"'.$rowCustomer[$i]->vPhone.'",';
						$csv_output .= 	'"'.$rowCustomer[$i]->fax.'",';
						$csv_output .= 	'"'.$rowCustomer[$i]->analysis1.'",';
						$csv_output .= 	'"'.$rowCustomer[$i]->analysis2.'",';
						$csv_output .=  '"'.$rowCustomer[$i]->analysic3.'",';
						$csv_output .= 	    $rowCustomer[$i]->departmentNo.',';
						$csv_output .=  '"'.$rowCustomer[$i]->vatRegistrationNo.'",';
						$csv_output .= 	    $rowCustomer[$i]->turnoverMTD.',';
						$csv_output .= 	    $rowCustomer[$i]->turnoverYID.',';
						$csv_output .= 		$rowCustomer[$i]->priorYID.',';
						$csv_output .= 		$rowCustomer[$i]->creditLimit.',';
						$csv_output .= 	'"'.$rowCustomer[$i]->terms.'",';
						$csv_output .= 		$rowCustomer[$i]->settlementDueDays.',';
						$csv_output .= 		$rowCustomer[$i]->settlementDiscountRate.',';						
						$csv_output .= 	'"'.$rowCustomer[$i]->nominalCode.'",';
						$csv_output .= 	'"'.$rowCustomer[$i]->textCode.'",';
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
							   	vPhone,
								''    as fax,
								''    as analysis1,
								''    as analysis2,
								''    as analysic3,
								0     as departmentNo,
								''    as vatRegistrationNo,
								0.00  as turnoverMTD,
								0.00  as turnoverYID,
								0.00  as priorYID,
								0.00  as creditLimit,
								'' 	  as terms,
								0     as settlementDueDays,
								0.00  as settlementDiscountRate,
								'4000' as nominalCode,
								'T1'  as textCode FROM ".SUPPLIERS." WHERE iStatus = 1";
					    
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
				
		if($this->err==0)
		{			
			if($recordCount>0)
				{	
					$csv_output ="";
										
					for($i=0;$i<$recordCount;$i++)
					{										
						# QUERY TO GET STATE
						$this->obDb->query = "SELECT vStateName FROM ".STATES." WHERE iStateId_PK = '".$rowSupplier[$i]->stateName."'";
						$stateRow = $this->obDb->fetchQuery();												
						$rowSupplier[$i]->stateName   = $stateRow[0]->vStateName;
						$rowSupplier[$i]->accountRef  =  strtoupper(substr($rowSupplier[$i]->Name,0,3)).$rowSupplier[$i]->iVendorid_PK;
						
						$rowSupplier[$i]->nominalCode = SAGE_NOMINAL_CODE;
								
						$csv_output .= 	'"'.$rowSupplier[$i]->accountRef.'",';		 
						$csv_output .= 	'"'.$rowSupplier[$i]->Name.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->vAddress1.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->vAddress2.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->vCity.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->stateName.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->vZip.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->contactName.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->vPhone.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->fax.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->analysis1.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->analysis2.'",';
						$csv_output .=  '"'.$rowSupplier[$i]->analysic3.'",';
						$csv_output .= 	    $rowSupplier[$i]->departmentNo.',';
						$csv_output .=  '"'.$rowSupplier[$i]->vatRegistrationNo.'",';
						$csv_output .= 	    $rowSupplier[$i]->turnoverMTD.',';
						$csv_output .= 	    $rowSupplier[$i]->turnoverYID.',';
						$csv_output .= 		$rowSupplier[$i]->priorYID.',';
						$csv_output .= 		$rowSupplier[$i]->creditLimit.',';
						$csv_output .= 	'"'.$rowSupplier[$i]->terms.'",';
						$csv_output .= 		$rowSupplier[$i]->settlementDueDays.',';
						$csv_output .= 		$rowSupplier[$i]->settlementDiscountRate.',';						
						$csv_output .= 	'"'.$rowSupplier[$i]->nominalCode.'",';
						$csv_output .= 	'"'.$rowSupplier[$i]->textCode.'",';
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
	 
	 
	#FUNCTION TO GENERATE CSV For Audit Trail Transactions (Orders)	
	function m_generateCsvAudit()
	{
	  
		$this->err=0;				
		 /*
		$inc_downloaded = false;
		if(!$this->libFunc->m_isNull($_REQUEST['inc_downloaded']))
		{			
			$inc_downloaded = true;
		}
		*/
	
		$postsalereceipt_check = false;
		
		if(isset($_POST['postsalereceipt_check']))
		{			
			$postsalereceipt_check = true;
			
			# CHECK POST "SALE RECEIPT TO" IF ITS BLANK N RETURN ERROR MESSAGE
			if(isset($_REQUEST['postsalereceipt_text']) && ($_REQUEST['postsalereceipt_text'] != ""))			{
				$postsalereceipt_text = $_REQUEST['postsalereceipt_text'];
			}
			else
			{
				$this->err=1;
				$this->errMsg="<strong>Post Receipts</strong> cannot leave empty <br>";
			}			
		}				
		
		

		# CHECK NOMINAL CODE IF ITS BLANK N RETURN ERROR MESSAGE
		if(!$this->libFunc->m_isNull($_REQUEST['nominalCode']) &&  $this->libFunc->ifSet($_REQUEST,'nominalCode',""))
		{
			$nominalCode = $_REQUEST['nominalCode'];
		}
		else
		{
			$this->err=1;
			$this->errMsg="<strong>Nominal Code </strong> cannot leave empty <br>";
		}
		
		
		
		
		#QUERY RETRIEVE INFORMATION FOR ORDER TABLE
		$this->obDb->query  = " SELECT distinct '' as transactionType, vLastName, iCustomerid_FK, 
							    iInvoice 		   as bankAccountRef,
								'4000'   		   as nominalCode,
								0    	           as departmentNo,
								tmOrderDate   	   as transactionDate,
								iInvoice 		   as invoice,
								''     	 		   as transactionDetail,
								fTotalPrice		   as netAmount,
								''  	           as taxCode,
								'' 		 		   as taxAmount,
								vAltCountry, fTaxPrice, vCountry							
								FROM ".ORDERS." WHERE iOrderStatus = 1";
								
					
							    
		if(isset($this->startDate_order) & $this->startDate_order>0)
		{
			$this->obDb->query.=" AND tmOrderDate >='".$this->startDate_order."'";
		}
		else
		{
			$this->err=1;
			$this->errMsg="<strong>From date</strong> is invalid or empty <br>";
		}
		if(isset($this->endDate_order) & $this->endDate_order>0)
		{
			$this->obDb->query.=" AND tmOrderDate <='".$this->endDate_order."'";
		}
		else
		{
			$this->err=1;
			$this->errMsg.="<strong>To date</strong> is invalid or empty ";;
		}

		$rowOrder    = $this->obDb->fetchQuery();	
		$recordCount = $this->obDb->record_count;
		
		
		if($this->err==0)
		{			
			if($recordCount>0)
				{	
					$csv_output ="";					
								
					for($i=0;$i<$recordCount;$i++)
					{										
					
						# QUERY TO GET TAXT CODE FROM SHIPPING COUNTRY
						$this->obDb->query = "SELECT vCountryName, vSageTaxCode, fTax FROM ".COUNTRY." WHERE iCountryId_PK = '".$rowOrder[$i]->vAltCountry."'";
						$countryRow = $this->obDb->fetchQuery();
						
						
						# QUERY TO GET TAXT CODE FROM SHIPPING COUNTRY
						$this->obDb->query = "SELECT vCountryName, vSageTaxCode, fTax FROM ".COUNTRY." WHERE iCountryId_PK = '".$rowOrder[$i]->vCountry."'";
						$billingCountryRow = $this->obDb->fetchQuery();																		
						 	
						$rowOrder[$i]->bankAccountRef   =   strtoupper(substr($rowOrder[$i]->vLastName,0,3)).$rowOrder[$i]->iCustomerid_FK;	
						$rowOrder[$i]->nominalCode 	= 	$nominalCode;															
								
						$csv_output .= 	'"SI",';		 
						$csv_output .= 	'"'.$rowOrder[$i]->bankAccountRef.'",';
						$csv_output .= 	'"'.$rowOrder[$i]->nominalCode.'",';
						$csv_output .= 		$rowOrder[$i]->departmentNo.',';
						$csv_output .= 	'"'.$this->libFunc->dateFormat2($rowOrder[$i]->transactionDate).'",';
						$csv_output .= 	'"'.$rowOrder[$i]->invoice.'",';
						$csv_output .= 	'" Web Order #'.$rowOrder[$i]->invoice.'",';
						$csv_output .= 		$rowOrder[$i]->netAmount.',';
						$csv_output .= 	'"'.$countryRow[0]->vSageTaxCode.'",';
						$csv_output .= 		$countryRow[0]->fTax;
						$csv_output .=  " \n";
						
						if ($postsalereceipt_check) {
							
						$csv_output .= 	'"SA",';		 
						$csv_output .= 	'"'.$rowOrder[$i]->bankAccountRef.'",';
						$csv_output .= 	'"'.$postsalereceipt_text.'",';
						$csv_output .= 		$rowOrder[$i]->departmentNo.',';
						$csv_output .= 	'"'.$this->libFunc->dateFormat2($rowOrder[$i]->transactionDate).'",';
						$csv_output .= 	'"'.$rowOrder[$i]->invoice.'",';
						$csv_output .= 	'" Payment - Web Order #'.$rowOrder[$i]->invoice.'",';
						
						$newPrice 	 = 		$rowOrder[$i]->netAmount - $rowOrder[$i]->fTaxPrice;
						$csv_output .= 		$newPrice.',';
						$csv_output .= 	'"'.$billingCountryRow[0]->vSageTaxCode.'",';
						$csv_output .= 		$billingCountryRow[0]->fTax;
						$csv_output .=  " \n";
						}
																    
					}	
					//$data=$this->csv->create_csv_file($arr_data,0);
					//$this->csv->forceFileDownload($data,"customer.csv",$type="application/vnd.ms-excel");		
					header( "Content-Type: application/save-as" );
					header( 'Content-Disposition: attachment; filename=transaction.csv');
				    print $csv_output;
				    exit;  				
				}		
				else {
					$errMsg=1;
					$this->m_setTempate();
					$this->errMsg="Sorry, no order found in selected date range! <br>";
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
		
		
		
	}# END OF AUDIT FUNCTION 
}
$obCsv=new c_sageCsv();
?>
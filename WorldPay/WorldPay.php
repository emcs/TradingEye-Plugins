<?php

class WorldPay
{
	function __construct()
	{
		$this->threedmode = 0;
		define("WorldPayExponent",2);
		$this->resultArray = array(
			"currentTag"  => "",
			"orderCode"   => "",
			"referenceID" => "",
			"errorcode"   => ""
		);
		
	}
	function RedirectForm()
	{
		if(GATEWAY_TESTMODE==1)
		{
			$this->url = "https://secure-test.worldpay.com/wcc/purchase";
			$test = '<input type="hidden" name="testMode" value="100">
			';
		}
		else
		{
			$this->url = "https://secure.worldpay.com/wcc/purchase";
			$test = '';
		}
		$html = '<!DOCTYPE HTML><html><head><title>Please Wait...</title><body><form id="myform" action="'.$this->url.'" method="post">Please Wait While You Are Transferred To WorldPay To Complete Your Transaction.
		'.$test.'<input type="hidden" name="instId" value="'.WorldPayRedirectInstallationId.'">
		<input type="hidden" name="cartId" value="'.$_SESSION['order_id'].'">
		<input type="hidden" name="MC_callback" value="'.SITE_SAFEURL.'ecom/index.php?action=checkout.wpcb">
		<input type="hidden" name="amount" value="'.$_SESSION['grandTotal'].'">
		<input type="hidden" name="currency" value="'.WorldPayRedirectCurrencyCode.'">
		<input type="hidden" name="desc" value="Order  '.$_SESSION['iInvoice'].'">
		<input type="hidden" name="email" value="'.$_SESSION['email'].'">
		<input type="hidden" name="name" value="AUTHORISED">
		<noscript><input type="submit" value="Click Here To Continue"></noscript>
		<script>document.getElementById(\'myform\').submit();</script>
		</form></body></html>';
		echo $html;
	}
	function DirectPayment()
	{
		if(GATEWAY_TESTMODE==1)
		{
			$this->url = "https://".WorldPayMerchantCode.":".WorldPayPass."@secure-test.worldpay.com/jsp/merchant/xml/paymentService.jsp";
		}
		else
		{
			$this->url = "https://".WorldPayMerchantCode.":".WorldPayPass."@secure.worldpay.com/jsp/merchant/xml/paymentService.jsp";
		}
		
		
		$xml="<?xml version='1.0' encoding=\"UTF-8\"?>
<!DOCTYPE paymentService PUBLIC '-//WorldPay/DTD WorldPay PaymentService v1//EN' 'http://dtd.worldpay.com/paymentService_v1.dtd'>
<paymentService version='1.4' merchantCode='".WorldPayMerchantCode."'>
<submit>
<order orderCode='".$_SESSION['order_id']."' installationId='".WorldPayInstallationId."'>
<description>PDD webshop</description>
<amount value='".$this->ExponentAmount($_SESSION['grandTotal'])."' currencyCode='".WorldPayCurrencyCode."' exponent='".WorldPayExponent."'/>
<orderContent><![CDATA[]]></orderContent>
<paymentDetails>";
		$issuenumber = "";
		$startdate="";
		switch($_SESSION['cc_type'])
		{
			case "SWITCH":
				$ptype = "MAESTRO-SSL";
				$issuenumber = "<issueNumber>".$_SESSION['issuenumber']."</issueNumber>";
				$startdate = "<startDate><date month='".$_SESSION['cc_start_month']."' year='".$_SESSION['cc_start_year']."'/></startDate>";
			break;
			case "VISA":
				$ptype = "VISA-SSL";
			break;
			case "DELTA":
				$ptype = "VISA-SSL";
			break;
			case "UKE":
				$ptype = "VISA-SSL";
			break;
			case "MC":
				$ptype = "ECMC-SSL";
			break;
			case "AMEX":
				$ptype = "AMEX-SSL";
			break;
			case "DinersClub":
				$ptype = "DINERS-SSL";
			break;
			case "DISCOVER":
				$ptype = "DISCOVER-SSL";
			break;
			case "SOLO":
				$ptype = "SOLO_GB-SSL";
				$issuenumber = "
<issueNumber>".$_SESSION['issuenumber']."</issueNumber>";
				$startdate = "<startDate><date month='".$_SESSION['cc_start_month']."' year='".$_SESSION['cc_start_year']."'/></startDate>";
			break;
			default:
				//die('No Payment Method');
  			break;
		}
		$xml = $xml . "<".$ptype."><cardNumber>".$_SESSION['cc_number']."</cardNumber><expiryDate><date month='".$_SESSION['cc_month']."' year='".$_SESSION['cc_year']."'/></expiryDate>".$startdate.$issuenumber."<cardHolderName>".$_SESSION['cardholder_name']."</cardHolderName>";
		$xml = $xml . "<cvc>".$_SESSION['cv2']."</cvc>";
		$xml = $xml . "<cardAddress><address><firstName>".$_SESSION['first_name']."</firstName><lastName>".$_SESSION['last_name']."</lastName><address1>".$_SESSION['address1']."</address1><address2>".$_SESSION['address2']."</address2><address3></address3><postalCode>".$_SESSION['zip']."</postalCode><city>".$_SESSION['city']."</city><countryCode>".$this->ISOCountryCode($_SESSION['bill_country_id'])."</countryCode><telephoneNumber>".$_SESSION['phone']."</telephoneNumber></address></cardAddress></".$ptype."><session shopperIPAddress='".$_SERVER['REMOTE_ADDR']."' id='".SESSIONID."' />";
		if($this->threedmode === 1)
		{
			$xml = $xml . "<info3DSecure><paResponse>".$_POST['PaRes']."</paResponse></info3DSecure>";
		}
		$xml = $xml . "</paymentDetails>
<shopper><shopperEmailAddress>".$_SESSION['email']."</shopperEmailAddress><browser><acceptHeader>".$_SERVER['HTTP_ACCEPT']."</acceptHeader><userAgentHeader>".$_SERVER['HTTP_USER_AGENT']."</userAgentHeader></browser></shopper>
<shippingAddress><address><firstName>".$_SESSION['alt_fName']."</firstName><lastName>".$_SESSION['alt_lName']."</lastName><address1>".$_SESSION['alt_address1']."</address1><address2>".$_SESSION['alt_address2']."</address2><address3></address3><postalCode>".$_SESSION['alt_zip']."</postalCode><city>".$_SESSION['alt_city']."</city><countryCode>".$this->ISOCountryCode($_SESSION['ship_country_id'])."</countryCode><telephoneNumber>".$_SESSION['alt_phone']."</telephoneNumber></address></shippingAddress>
";
		if($this->threedmode === 1)
		{
			$xml = $xml . "
<echoData>".$_SESSION['echoData']."</echoData>";
		}
		else
		{
			$_SESSION['newc'] = 1;
		}
		$xml = $xml . "</order>
</submit>
</paymentService>";
		//0:direct response,1:3d secure second order response
		//$this->xmlMode = 0;
		$this->sendData($xml);
	}
	function ISOCountryCode($id)
	{
		$this->obDb->query = "SELECT vShortName FROM ".COUNTRY." WHERE iCountryId_PK='".$id."'";
		$result = $this->obDb->fetchQuery();
		return $result[0]->vShortName;
	}
	function CountryName($id)
	{
		$this->obDb->query = "SELECT vCountryName FROM ".COUNTRY." WHERE iCountryId_PK='".$id."'";
		$result = $this->obDb->fetchQuery();
		return $result[0]->vCountryName;
	}
	function ExponentAmount($amount)
	{
		$x = 0;
		$y = "1";
		while($x < WorldPayExponent)
		{
			$y = $y . "0";
			$x = $x + 1;
		}
		return (int)($amount * $y);
	}
	function StartElement($parser, $name, $attrs) 
	{
		$this->resultArray['currentTag'] = $name;
		switch ($name) {
			case "ERROR": 
				$this->resultArray['errorcode'] = $attrs['CODE']; //example of how to catch the error code number (i.e. 1 to 7)
				// $url_error = "error_order.php";
			break;
			case "REFERENCE":
				$this->resultArray['referenceID'] = $attrs['ID'];//for storage in your own database
  			break;
			case "ORDERSTATUS":
				$this->resultArray['orderCode'] = $attrs['ORDERCODE'];
  			break;
			case "REFUNDRECEIVED":
				$this->resultArray['orderCode'] = $attrs['ORDERCODE'];
				$this->resultArray['refund2'] = 1;
  			break;
			case "OK":
				$this->resultArray['refund'] = 1;
  			break;
		}
	}
  
	function EndElement($parser, $name) {
		$this->resultArray['currentTag'] = "";
	}
  
	function CharacterData($parser, $result) {
	
		//echo $this->resultArray['currentTag']."|".$result."<br/><br/>";
		switch ($this->resultArray['currentTag']) {
			case "REFERENCE":
				//there is a REFERENCE so there must be an url which was provided by bibit for the actual payment. echo $result;
				$this->resultArray['url_togoto'] = $result;
			break;
			case "ISSUERURL":
				$this->resultArray['3dgoto'] = $result;
			break;
			case "PAREQUEST":
				$this->resultArray['paRequest'] = $result;
			break;
			case "ECHODATA":
				$_SESSION['echoData'] = $result;
			break;
			case "ERROR":
				$this->resultArray['error_details'] = $result;
			break;
			case "LASTEVENT":
				$this->resultArray['lastEvent'] = $result;
			break;
			default:
			break;
		}
	}
	
	function ParseXML($bibitResult) {
		$this->xml_parser = xml_parser_create();
		// set callback functions
		xml_set_object($this->xml_parser,$this);
		xml_set_element_handler($this->xml_parser, "StartElement", "EndElement");
		xml_set_character_data_handler($this->xml_parser, "characterData");
		if (!xml_parse($this->xml_parser, $bibitResult))
		{
			//die(sprintf("XML error: %s at line %d",
			//xml_error_string(xml_get_error_code($this->xml_parser)),
			//xml_get_current_line_number($this->xml_parser)));
		}
		// clean up
		xml_parser_free($this->xml_parser);
	}
	
	function sendData($xml)
	{
		$ch = curl_init ($this->url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,utf8_encode($xml));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if(isset($_SESSION['thec']))
		{
			curl_setopt($ch, CURLOPT_COOKIE, $_SESSION['thec']);
			unset($_SESSION['thec']);
		}
		curl_setopt($ch, CURLOPT_HEADER, 1);
		$this->error = "";
		$result = curl_exec ($ch); // result will contain XML reply from WorldPay curl_close ($ch);
		if($result == false)
		{
			//die("Curl could not retrieve page '".$this->url."', curl_exec returns false");
	
		}
		else
		{
			//echo $result."<br/><br/>";
			$result = explode('<?xml version="1.0" encoding="UTF-8"?>',$result);
			$this->ParseXML('<?xml version="1.0" encoding="UTF-8"?>'.$result[1]);
			if(isset($_SESSION['newc']) && $_SESSION['newc'] === 1)
			{	
				$start = strpos($result[0],'Set-Cookie: ');
				$cookie = substr($result[0],$start,strpos($result[0],"\n",$start+11)-$start-11);
				$_SESSION['thec'] = $cookie;
			}
			if(isset($this->resultArray['orderCode']) && !empty($this->resultArray['orderCode']))
			{
				if(isset($this->resultArray['errorcode']) && !empty($this->resultArray['errorcode']))
				{
					//die($result);
					$this->obDb->query = "UPDATE ".ORDERS." SET vAuthCode='".$this->resultArray['error_details']."' WHERE iOrderid_PK='".$this->resultArray['orderCode']."'";
					$this->obDb->updateQuery();
					$_SESSION['cardsave_error'] = $this->resultArray['error_details'];
					$retUrl=$this->libFunc->m_safeUrl(SITE_SAFEURL."ecom/index.php?action=checkout.billing");
					$this->libFunc->m_mosRedirect($retUrl);
				}
				if(isset($this->resultArray['3dgoto']) && !empty($this->resultArray['3dgoto']))
				{
					$_SESSION['newc'] = 0;
					$_SESSION['first'] = $xml;
					echo '<html>
<head>
    <title>3-D Secure helper page</title>
</head>
<body OnLoad="OnLoadEvent();">
This page should forward you to your own card issuer for identification.
If your browser does not start loading the page, press the button you see.
<br/>
After you successfully identify yourself you will be sent back to this site
where the payment process will continue as if nothing had happened.
<br/>
implemented...
<form name="issuerForm" method="POST" action="'.$this->resultArray['3dgoto'].'" >
<input type="hidden" name="PaReq" value="'.$this->resultArray['paRequest'].'" />
<input type="hidden" name="TermUrl" value="'.SITE_SAFEURL.'ecom/index.php?action=checkout.wpcb&3d=1" />
<input type="submit" name="Identify yourself" />
</form>
<script language="Javascript">
<!--
function OnLoadEvent() {
document.issuerForm.submit(); }
// -->
</script>
</body>
</html>';
				}
				elseif(isset($this->resultArray['url_togoto']) && !empty($this->resultArray['url_togoto']))
				{
					//die($result);
				}
				elseif(isset($this->resultArray['lastEvent']) && !empty($this->resultArray['lastEvent']))
				{
					if($this->resultArray['lastEvent'] === "AUTHORISED" || $this->resultArray['lastEvent'] === "CAPTURED")
					{
					//die($result);
						$this->obDb->query = "UPDATE ".ORDERS." SET iPayStatus='1',iOrderStatus='1',vAuthCode='".$this->resultArray['lastEvent']."' WHERE iOrderid_PK='".$this->resultArray['orderCode']."'";
						$this->obDb->updateQuery();
						$retUrl=$this->libFunc->m_safeUrl(SITE_SAFEURL."ecom/index.php?action=checkout.process&mode=".$this->resultArray['orderCode']);
						$this->libFunc->m_mosRedirect($retUrl);
					}
					else
					{
						//die($result);
						$this->obDb->query = "UPDATE ".ORDERS." SET vAuthCode='".$this->resultArray['lastEvent']."' WHERE iOrderid_PK='".$this->resultArray['orderCode']."'";
						$this->obDb->updateQuery();
						$_SESSION['cardsave_error'] = $this->resultArray['lastEvent'];
						$retUrl=$this->libFunc->m_safeUrl(SITE_SAFEURL."ecom/index.php?action=checkout.billing");
						$this->libFunc->m_mosRedirect($retUrl);
					}
				}
				elseif(isset($this->resultArray['refund']) && $this->resultArray['refund'] === 1 && isset($this->resultArray['refund2']) && $this->resultArray['refund2'] === 1)
				{
					$this->obDb->query = "UPDATE ".ORDERS." SET iPayStatus='0',iOrderStatus='0',vAuthCode='REFUNDED' WHERE iOrderid_PK='".$this->resultArray['orderCode']."'";
					$this->obDb->updateQuery();
					$retUrl=$this->libFunc->m_safeUrl(SITE_URL."order/adminindex.php?action=orders.dspDetails&orderid=".$this->resultArray['orderCode']);
					$this->libFunc->m_mosRedirect($retUrl);
				}
				else
				{
					//die($result);
				}
			}
			//die($result);
		}
	}
	
	function WorldPayCallback()
	{
		if(isset($this->request['3d']) && $this->request['3d'] == '1')
		{
			$this->threedmode = 1;
			$this->DirectPayment();
		}
		elseif(isset($this->request['amount']) && is_numeric($this->request['amount']) && isset($this->request['transId']) && !empty($this->request['transId']) && isset($this->request['transStatus']) && !empty($this->request['transStatus']) && isset($this->request['cartId']) && is_numeric($this->request['cartId']) && isset($_SERVER['HTTP_HOST']))
		{
			$this->obDb->query = "SELECT fTotalPrice FROM ".ORDERS." WHERE iOrderid_PK='".$this->request['cartId']."'";
			$result = $this->obDb->fetchQuery();
			
			if($result[0]->fTotalPrice == $this->request['amount'])
			{
				$billship = "";
				//if(isset($this->request['name']) && !empty($this->request['name']) && isset($this->request['address1']) && !empty($this->request['address1']) && isset($this->request['address2']) && !empty($this->request['address2']) && isset($this->request['town']) && !empty($this->request['town']) && isset($this->request['']) && !empty($this->request['']) && isset($this->request['']) && !empty($this->request['']) && isset($this->request['']) && !empty($this->request['']) && isset($this->request['']) && !empty($this->request['']))
				//paid
				if($this->request['transStatus'] === "Y")
				{
					$this->obDb->query = "UPDATE ".ORDERS." SET iPayStatus='1',iOrderStatus='1',vAuthCode='AUTHORISED',iTransactionId='".$this->request['transId']."' WHERE iOrderid_PK='".$this->request['cartId']."'";
					$this->obDb->updateQuery();
					$obreceipt=new c_receipt();
					$obreceipt->m_sendOrderDetails($this->request['cartId']);
				}
			}
		}
	}
	
	//Refunds 100% of the purchase
	function Refund($orderid)
	{
		if(GATEWAY_TESTMODE==1)
		{
			$this->url = "https://".WorldPayMerchantCode.":".WorldPayPass."@secure-test.worldpay.com/jsp/merchant/xml/paymentService.jsp";
		}
		else
		{
			$this->url = "https://".WorldPayMerchantCode.":".WorldPayPass."@secure.worldpay.com/jsp/merchant/xml/paymentService.jsp";
		}
		$this->obDb->query = "SELECT fTotalPrice FROM ".ORDERS." WHERE iOrderid_PK='".$orderid."'";
		$result = $this->obDb->fetchQuery();
		$xml = '<?xml version="1.0"?>
		<!DOCTYPE paymentService PUBLIC "-//WorldPay//DTD WorldPay PaymentService v1//EN"
		"http://dtd.worldpay.com/paymentService_v1.dtd">
		<paymentService merchantCode="'.WorldPayMerchantCode.'" version="1.4">
		<modify>
		<orderModification orderCode="'.$orderid.'">
		<refund>
		<amount value="'.$this->ExponentAmount($result[0]->fTotalPrice).'" currencyCode="'.WorldPayCurrencyCode.'" exponent="'.WorldPayExponent.'"
		debitCreditIndicator="credit"/>
		</refund>
		</orderModification>
		</modify>
		</paymentService>';
		$this->sendData($xml);
	}
}
?>
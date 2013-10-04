<?php
 
class c_Paypal_Express
{

function c_Paypal_Express()
{
	if(GATEWAY_TESTMODE==1)
	{
		$this->environment = "sandbox";
	}
	else
	{
		$this->environment = "live";
	}
}
 
function PPHttpPost($methodName_, $nvpStr_)
{
	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode(PAYPAL_APIID);
	$API_Password = urlencode(PAYPAL_PASS);
	$API_Signature = urlencode(PAYPAL_SIG);
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {
		$API_Endpoint = "https://api-3t.$this->environment.paypal.com/nvp";
	}
	$version = urlencode('74.0');
 
	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
 
	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
 
	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
 
	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
 
	// Get response from the server.
	$httpResponse = curl_exec($ch);
 
	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}
 
	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);
 
	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}
 
	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}
 
	return $httpParsedResponseAr;
}

//starts a checkout
//NEED TO ADD A GCHECKOUT STYLE POSTAGE And SSL CALLBACK Postage for TE calculated shipping

function CreateNVP()
{
	// Set request-specific fields.
	$currencyID = urlencode(PAYMENT_CURRENCY);							// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
	$paymentType = urlencode('Sale');				// Authorization or 'Sale' or 'Order'
//ITEMIZATION + DISCOUNTS + GIFT WRAP
	$this->obDb->query ='SELECT p.iProdId_PK AS pid,p.vTitle as title,p.tShortDescription as pdesc,p.iFreeShip as freeship,p.iTaxable as taxable,p.fPrice as price,t.iQty as qty, p.vSku as sku, g.vTitle as giftwraptitle, t.iGiftWrap as giftwrap, g.fPrice as gift_price, t.fVolDiscount as voldiscount, p.fItemWeight as weight, GROUP_CONCAT(o.vName) as option_name, GROUP_CONCAT(ov.vOptSku) as optsku, GROUP_CONCAT(ov.vItem) as option_value, GROUP_CONCAT(ov.fPrice) as option_price FROM '.TEMPCART.' as t
	LEFT JOIN '.TEMPOPTIONS.' as topt on t.iTmpCartId_PK=topt.iTmpCartId_FK
	INNER JOIN '.PRODUCTS.' as p on t.iProdId_FK=p.iProdid_PK
	LEFT JOIN '.OPTIONVALUES.' as ov on ov.iOptionValueid_PK=topt.vOptVal
	LEFT JOIN '.PRODUCTOPTIONS.' as po on po.iOptionid_PK=topt.iOptId_FK
	LEFT JOIN '.OPTIONS.' as o on po.iOptionid=o.iOptionid_PK
	LEFT JOIN '.GIFTWRAPS.' as g on t.iGiftWrap=g.iGiftwrapid_PK
	WHERE t.vSessionId="'.SESSIONID.'" AND t.iBackOrder="0"
	GROUP BY pid';
	$itemResult=$this->obDb->fetchQuery();
	$runningtotal = 0;
	$cartweight = 0;
	$x = 0;
	$postageqty = 0;
	$itemqty = 0;
	$taxtotal = 0;
	$nvpStr ="";
	$checkoutStr ="";
	foreach($itemResult as $key => $value)
	{
		$price = $itemResult[$key]->price;
		$title = $itemResult[$key]->title;
		$desc = $itemResult[$key]->pdesc;
		if(isset($itemResult[$key]->option_price) && !empty($itemResult[$key]->option_price))
		{
			$desc = $desc . " Options: " . $itemResult[$key]->option_value;
			$tempprice = explode(",",$itemResult[$key]->option_price);
			foreach($tempprice as $key2 => $value2)
			{
				$price = $price + $tempprice[$key2];
			}
		}
		if(!empty($itemResult[$key]->weight))
		{
			$cartweight = $cartweight + $itemResult[$key]->weight;
		}
		if($itemResult[$key]->giftwrap != 0)
		{
			$price = $price + $itemResult[$key]->gift_price;
			$title = $title . " Gift Wrapped: " . $itemResult[$key]->giftwraptitle;
		}
		if($itemResult[$key]->voldiscount != 0)
		{
			$voldiscount = $voldiscount + ($itemResult[$key]->qty * ($itemResult[$key]->voldiscount));
		}
		$runningtotal = $runningtotal + ($price * $itemResult[$key]->qty);
		if($itemResult[$key]->freeship == "1")
		{
			$postageqty = $postageqty + $itemResult[$key]->qty;
		}
		$itemqty = $itemqty + $itemResult[$key]->qty;
		$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_NAME".$x."=".urlencode($title);
		$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_NUMBER".$x."=".urlencode($itemResult[$key]->sku);
		$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_DESC".$x."=".urlencode($desc);
		$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_AMT".$x."=".urlencode(number_format($price,2));
		$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_QTY".$x."=".urlencode($itemResult[$key]->qty);
		$iqty = $itemResult[$key]->qty;
		$ititle = $title;
		$idesc = $desc;
		$iprice = number_format($price,2);
		$itotal = number_format($price*$iqty,2);
		$checkoutStr = $checkoutStr . "<tr><td>$iqty</td><td>$ititle</td><td>$idesc</td><td>$iprice</td><td>$itotal</td></tr>";
		if($itemResult[$key]->taxable == "1" or $itemResult[$key]->taxable == 1)
		{
			$taxtotal = $itemResult[$key]->qty * ($taxtotal + $price);
		}
		$x = $x + 1;
	}
	
	
	//DISCOUNTS
		$promotionDiscount=$this->comFunc->m_calculatePromotionDiscount($runningtotal);
		$shipdiscount = 0;
				if($promotionDiscount>=0)
				{
					if($promotionDiscount==0)
					{
						$shipdiscount = "free";
					}
					else
					{
						$displayDiscount = "Discount";
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_NAME".$x."=".urlencode($displayDiscount);
		$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_NUMBER".$x."=";
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_DESC".$x."=".urlencode($displayDiscount);
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_AMT".$x."=".urlencode(number_format(-$promotionDiscount,2));
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_QTY".$x."=1";
						$x = $x + 1;
						$runningtotal = $runningtotal - $promotionDiscount;
						$taxtotal = $taxtotal - $promotionDiscount;
		$iqty = 1;
		$ititle = $displayDiscount;
		$idesc = $displayDiscount;
		$iprice = number_format(-$promotionDiscount,2);
		$itotal = number_format($price*$iqty,2);
		$checkoutStr = $checkoutStr . "<tr><td>$iqty</td><td>$ititle</td><td>$idesc</td><td>$iprice</td><td>$itotal</td></tr>";
					}
		
				}
				if(!empty($voldiscount))
				{
						$displayDiscount = "Discount";
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_NAME".$x."=".urlencode("Volume Discount");
		$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_NUMBER".$x."=";
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_DESC".$x."=";
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_AMT".$x."=-".urlencode(number_format($voldiscount,2));
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_QTY".$x."=1";
						$x = $x + 1;
						$runningtotal = $runningtotal - $voldiscount;
						$taxtotal = $taxtotal - $voldiscount;
		$iqty = 1;
		$ititle = $voldiscount;
		$idesc = "";
		$iprice = number_format(-$voldiscount,2);
		$itotal = number_format($price*$iqty,2);
		$checkoutStr = $checkoutStr . "<tr><td>$iqty</td><td>$ititle</td><td>$idesc</td><td>$iprice</td><td>$itotal</td></tr>";
				}

		if(isset($_SESSION['discountPrice']) && !empty($_SESSION['discountPrice']) && isset($_SESSION['discountMini']) && $_SESSION['discountMini'] < $runningtotal)
		{
			if($_SESSION['discountType'] == "fixed")
			{
				$discount = $_SESSION['discountPrice'];
				$runningtotal = $runningtotal - $discount;
						$taxtotal = $taxtotal - $voldiscount;
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_NAME".$x."=".urlencode("Discount Code (".$_SESSION['discountCode'].")");
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_NUMBER".$x."=".urlencode($itemResult[$key]->sku);
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_DESC".$x."=".urlencode("Discount Code (".$_SESSION['discountCode'].")");
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_AMT".$x."=".urlencode(number_format(-$discount));
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_QTY".$x."=1";
		$iqty = 1;
		$ititle = "Discount Code (".$_SESSION['discountCode'].")";
		$idesc = "";
		$iprice = number_format(-$discount,2);
		$itotal = number_format($price*$iqty,2);
		$checkoutStr = $checkoutStr . "<tr><td>$iqty</td><td>$ititle</td><td>$idesc</td><td>$iprice</td><td>$itotal</td></tr>";
						$x = $x + 1;
			}
			else
			{
				$discount = $_SESSION['discountPrice'] / 100;
				$discount = $runningtotal * $discount;
				
				$runningtotal = $runningtotal - $discount;
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_NAME".$x."=".urlencode("Discount Code (".$_SESSION['discountCode'].")");
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_DESC".$x."=".urlencode("Discount Code (".$_SESSION['discountCode'].")");
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_AMT".$x."=".urlencode(number_format(-$discount));
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_QTY".$x."=1";
		$iqty = 1;
		$ititle = "Discount Code (".$_SESSION['discountCode'].")";
		$idesc = "";
		$iprice = number_format(-$discount,2);
		$itotal = number_format($price*$iqty,2);
		$checkoutStr = $checkoutStr . "<tr><td>$iqty</td><td>$ititle</td><td>$idesc</td><td>$iprice</td><td>$itotal</td></tr>";
						$x = $x + 1;
			}
		}
		if(isset($_SESSION['giftCertPrice']) && !empty($_SESSION['giftCertPrice']))
		{
			$runningtotal = $runningtotal - $_SESSION['giftCertPrice'];
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_NAME".$x."=".urlencode("Gift Certificate");
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_DESC".$x."=".urlencode("Gift Certificate");
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_AMT".$x."=".urlencode(number_format(-$_SESSION['giftCertPrice']));
						$nvpStr = $nvpStr . "&L_PAYMENTREQUEST_0_QTY".$x."=1";
		$iqty = 1;
		$ititle = "Gift Certificate";
		$idesc = "";
		$iprice = number_format(-$_SESSION['giftCertPrice'],2);
		$itotal = number_format($price*$iqty,2);
		$checkoutStr = $checkoutStr . "<tr><td>$iqty</td><td>$ititle</td><td>$idesc</td><td>$iprice</td><td>$itotal</td></tr>";
						$x = $x + 1;
		}
		$itemAmt = number_format($runningtotal,2);
		$_SESSION['itemAmt'] = number_format($runningtotal,2);
		$cartWeightPrice = 0;
		if($cartWeight > 0 && ISACTIVE_ITEMWEIGHT == 1)
		{
			$cartWeightPrice = $cartWeight*DEFAULT_ITEMWEIGHT;
			$_SESSION['cartWeightPrice'] = $cartWeightPrice;
		}
		$_SESSION['cartWeight'] = $cartWeight;
		
	
	
	
	
	
	
	
	
	//SHIPPING
	if($shipdiscount === "free")
	{
		//this needs more attention
		$nvpStr = $nvpStr . "&PAYMENTREQUEST_0_SHIPPINGAMT=0";
		$shipamt = 0;
	}
	else
	{
		//if postage method isnt calculated based on location, and doesnt have special postage options on
		if(DEFAULT_POSTAGE_METHOD != 'zones' && DEFAULT_POSTAGE_METHOD != 'cities' && SPECIAL_POSTAGE != "1")
		{
			//PreCalc Shipping
			//$itemqty = total qty of items in cart;
			//$postageqty = total qty of free ship items in cart;
			$shipamt = $this->m_postagePrice($runningtotal,$itemqty,$postageqty);
			$shipamt = $shipamt + $cartWeightPrice;
			$nvpStr = $nvpStr . "&PAYMENTREQUEST_0_SHIPPINGAMT=" . number_format($shipamt,2);
			$_SESSION['postageMethod']=DEFAULT_POSTAGE_METHOD;
			$_SESSION['postagePrice']=$shipamt;
			if(VAT_POSTAGE_FLAG == 1)
			{
				$taxtotal = $taxtotal + $shipamt;
			}
		}
		else
		{
			//Shipping needs to be calculated based on their location or they have special options.
			$_SESSION['postageMethod']=DEFAULT_POSTAGE_METHOD;
			$_SESSION['postageqty'] = $postageqty;
			$_SESSION['itemqty'] = $itemqty;
			$_SESSION['cartWeightPrice'] = $cartWeightPrice;
			if(isset($_SESSION['postagePrice']))
			{
				unset($_SESSION['postagePrice']);
			}
		}
	}
	
	//TAX
	$_SESSION['runningVAT'] = $taxtotal;
	$taxtotal = $taxtotal * DEFAULTVATTAX / 100;
	$nvpStr = $nvpStr . "&PAYMENTREQUEST_0_TAXAMT=".number_format($taxtotal,2);
	$_SESSION['VAT'] = DEFAULTVATTAX;
	$_SESSION['vatTotal'] = $taxtotal;
	
	
	
	$_SESSION['checkoutStr'] = $checkoutStr;
	
	
	//TOTAL
	$total = $runningtotal + $taxtotal + $shipamt;
	$_SESSION['grandTotal'] = $total;
	$total = number_format($runningtotal + $taxtotal + $shipamt,2);
	
	
	//Gift CERT AND MEMBERS POINTS SHOULD GO HERE
	//MEMBERS POINTS TO BE ADDED LATER
	
	
	// Add request-specific fields to the request string.
	$nvpStr = $nvpStr."&PAYMENTREQUEST_0_ITEMAMT=$itemAmt&PAYMENTREQUEST_0_AMT=$total&PAYMENTREQUEST_0_PAYMENTACTION=$paymentType&PAYMENTREQUEST_0_CURRENCYCODE=$currencyID&ALLOWNOTE=1";
	
	return $nvpStr;
}

function SetExpressCheckout()
{
if(isset($_SESSION['userid']) && !empty($_SESSION['userid']))
	{
		$_SESSION['withoutlogin'] = 0;
	}
	 
	$returnURL = urlencode(SITE_SAFEURL."ecom/index.php?action=checkout.ppxr".$userid);
	$cancelURL = urlencode(SITE_SAFEURL."ecom/index.php?action=ecom.viewcart");	
	$nvpStr = "&RETURNURL=$returnURL&CANCELURL=$cancelURL";
	$nvpStr = $nvpStr . $this->CreateNVP();
	//die($nvpStr);
	// Execute the API operation; see the PPHttpPost function above.
	$httpParsedResponseAr = $this->PPHttpPost('SetExpressCheckout', $nvpStr);
	 
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
		// Redirect to paypal.com.
		$token = urldecode($httpParsedResponseAr["TOKEN"]);
		$payPalURL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=$token";
		if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {
			$payPalURL = "https://www.$this->environment.paypal.com/webscr&cmd=_express-checkout&token=$token";
		}
		header("Location: $payPalURL");
		exit;
	} else  {
		exit('SetExpressCheckout failed: ' . print_r($httpParsedResponseAr, true));
	}
}

function Paypal_Express_Image($image)
{
	//0 = checkout with paypal
	//1 = paypal logo for payment method
	if($image == 1)
	{
		$image = "ecmark";
	}
	else
	{
		$image = "ecshortcut";
	}
	$other = "&locale=en_GB&buttontype=".$image;
	$pal = "&METHOD=GetPalDetails&PAL=".PAYPAL_PALID;
	$payPalURL = "https://fpdbs.paypal.com/dynamicimageweb?cmd=_dynamic-image";
	if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {
		$payPalURL = "https://fpdbs.sandbox.paypal.com/dynamicimageweb?cmd=_dynamic-image";
	}
	return $payPalURL.$pal.$other;
	
}

//gets checkout info to calc final amount. Should add verification that paypal sent it?
function GetExpressCheckoutDetails($token)
{
	/**
	 * This example assumes that this is the return URL in the SetExpressCheckout API call.
	 * The PayPal website redirects the user to this page with a token.
	 */
	 
	// Set request-specific fields.
	$token = urlencode(htmlspecialchars($token));
	$_SESSION['ppxtoken'] = $token;
	// Add request-specific fields to the request string.
	$nvpStr = "&TOKEN=$token";
	$_SESSION['nvpspx'] = $this->CreateNVP();
	
	// Execute the API operation; see the PPHttpPost function above.
	$httpParsedResponseAr = $this->PPHttpPost('GetExpressCheckoutDetails', $nvpStr);
	 
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
		
		//DIE(print_r($httpParsedResponseAr,true));
		
		$street1 = "";
		$street2 = "";
		$first_name = "";
		$last_name = "";
		$business = "";
		$email = "";
		$city_name = "";
		$state_province = "";
		$postal_code = "";
		$country_code = "";
		$phonenum = "";
		$payerID = urldecode($httpParsedResponseAr['PAYERID']);
		$_SESSION['pppid'] = $payerID;
		$street1 = urldecode($httpParsedResponseAr["LASTNAME"]);
		
		if(array_key_exists("PHONENUM", $httpParsedResponseAr)) {
			$phonenum = urldecode($httpParsedResponseAr["PHONENUM"]);
		}
		$first_name = urldecode($httpParsedResponseAr["FIRSTNAME"]);
		$last_name = urldecode($httpParsedResponseAr["LASTNAME"]);
		
		if(array_key_exists("BUSINESS", $httpParsedResponseAr)) {
			$business = urldecode($httpParsedResponseAr["BUSINESS"]);
		}
		if(array_key_exists("EMAIL", $httpParsedResponseAr)) {
			$email = urldecode($httpParsedResponseAr["EMAIL"]);
		}
		if(!isset($_SESSION['userid']) && isset($email) && !empty($email))
		{
			$this->obDb->query = 'SELECT iCustmerid_PK FROM '.CUSTOMERS.' WHERE vEmail="'.$email.'" AND vPassword IS NOT NULL AND vPassword<>""';
			$this->obDb->fetchQuery();
			if($this->obDb->record_count > 0)
			{
				$_SESSION['userid'] = $result[0]->iCustmerid_PK;
				$_SESSION['withoutlogin'] = 0;
			}
		}
		$country = urldecode($httpParsedResponseAr["COUNTRYCODE"]);
		$ship_name = urldecode($httpParsedResponseAr["SHIPTONAME"]);
		$ship_address1 = urldecode($httpParsedResponseAr["SHIPTOSTREET"]);
		$ship_address2 = urldecode($httpParsedResponseAr["SHIPTOSTREET2"]);
		$ship_city_name = urldecode($httpParsedResponseAr["SHIPTOCITY"]);
		$ship_state_province = urldecode($httpParsedResponseAr["SHIPTOSTATE"]);
		$ship_postal_code = urldecode($httpParsedResponseAr["SHIPTOZIP"]);
		$ship_country_code = urldecode($httpParsedResponseAr["SHIPTOCOUNTRYCODE"]);
		
		//$this->obDb->query = "SELECT iStateId_PK FROM ".STATES." WHERE vStateName='".$ship_state_province."'";
		//$result = $this->obDb->fetchquery();
		//$shipstate = $result[0]->iStateId_PK;
		//$ship_country_code = $result[0]->iCountryId_PK;
		$ship_phone = urldecode($httpParsedResponseAr["SHIPTOPHONENUM"]);
		if(isset($httpParsedResponseAr["NOTE"]))
		{
			$notes = urldecode($httpParsedResponseAr["NOTE"]);
		}
		
	 
	 
		//Set all the required SESSIONS and send it through saveorder and have that send the final submit. Save lots of coding.
		
			
			$_SESSION['payMethod'] = "paypal_express";
			//Handling Discounts
				//$_SESSION['discountCode']=$this->request['discount'];
				//$this->discountPrice=$this->comFunc->m_calculateDiscount($this->request['discount'],);
			
			//Handling Gift certficates
				//$_SESSION['giftCertCode']=$this->request['giftcert'];
			//	$this->giftCertPrice=$this->comFunc->m_calculateGiftCertPrice($this->request['giftcert']);
		
			$_SESSION['customer']			='set';#CUSTOMER DATA IN SESSION
			$_SESSION['withoutlogin']	=1;
			
			$_SESSION['txtpassword']		="";
			$_SESSION['first_name']		=$first_name;
			$_SESSION['last_name']		=$last_name;
			$_SESSION['email']				=$email;
			$_SESSION['address1']			="";
			$_SESSION['address2']			="";
			$_SESSION['city']				="";	
			$_SESSION['bill_state_id']		="";
			$_SESSION['bill_state']			="";
			$_SESSION['bill_country_id']	=$this->getCountryId($country);
			$_SESSION['zip']					="";
			$_SESSION['comments']=$this->libFunc->m_displayContent($httpParsedResponseAr["NOTE"]);
            $_SESSION['company']			=$business;	
			$_SESSION['phone']				=$phonenum;
		
			$_SESSION['alt_ship']=0;
			$_SESSION['alt_name']			=$ship_name;
			$_SESSION['alt_fName']      	= "";
            $_SESSION['alt_lName']      	= "";
			$_SESSION['alt_address1']		=$ship_address1;
			$_SESSION['alt_address2']		=$ship_address2;
			$_SESSION['alt_city']			=$ship_city_name;
			$_SESSION['ship_state'] 		=$ship_state_province;
			$_SESSION['ship_country_id']	=$this->getCountryId($ship_country_code);

			$_SESSION['alt_zip']			=$ship_postal_code;
			$_SESSION['alt_phone']			=$ship_phone;

			$this->calcSetVat($this->getCountryId($ship_country_code),$ship_state_province);
			//calc state id using name? $ship_state_province
				//$_SESSION['ship_state_id']	 =$this->request['bill_state_id'];

		/*$obSaveOrder=new c_saveOrder();
		$obSaveOrder->obTpl=$this->obTpl;
		$obSaveOrder->PXobTpl=$this->obTpl;
		$obSaveOrder->obDb=$this->obDb;
		$obSaveOrder->request=$this->request;
		$obSaveOrder->m_saveOrderData();*/
		$this->obTpl->set_var("TPL_VAR_BREDCRUMBS","&nbsp;&raquo;&nbsp;Confirm Payment");
		$this->obTpl->set_var("TPL_VAR_BODY",$this->DoExpressCheckout_Prepare($ship_country_code,$ship_state_province));
		$_SESSION['ppxr2'] = "1";
	} else  {
		//FAILED
	}
}

function DoExpressCheckout_Prepare($country,$state)
{
	$this->ObTpl = new template();
	$this->ObTpl->set_file("TPL_PAYPAL_EXPRESS_REVIEW_FILE", $this->template);
	$this->ObTpl->set_block("TPL_PAYPAL_EXPRESS_REVIEW_FILE","TPL_SHIP_OPTIONS_BLK","ship_options_blk");
	$this->ObTpl->set_block("TPL_PAYPAL_EXPRESS_REVIEW_FILE","MPOINT_BLK","mpoint_blok");
	$this->ObTpl->set_block("MPOINT_BLK","MPOINT_USE_BLK","mpoint_use_blok");
	$this->ObTpl->set_var("ship_options_blk","");
	$this->ObTpl->set_var("mpoint_blok","");
	$this->ObTpl->set_var("mpoint_use_blok","");
	$this->ObTpl->set_var("LANG_VAR_REVIEWYOURORDER",LANG_REVIEWORDERTXT);
	$this->ObTpl->set_var("TPL_VAR_CURRENCY",CONST_CURRENCY);
	$this->ObTpl->set_var("TPL_VAR_FORMURL",SITE_SAFEURL."ecom/index.php?action=checkout.ppxr2");
	$this->ObTpl->set_var("TPL_VAR_ITEMS", $_SESSION['checkoutStr']);
	$this->ObTpl->set_var("TPL_VAR_ITEM_TOTAL", $_SESSION['runningVAT']);
	$this->ObTpl->set_var("TPL_VAR_SUBTOTAL", number_format($_SESSION['itemAmt'],2));
	if(MPOINTVALUE > 0 && OFFERMPOINT == 1)
	{
		$this->ObTpl->set_var('TPL_VAR_EARNEDMP',floor(MPOINTCALCULATION*$_SESSION['itemAmt']));
		if(isset($_SESSION['userid']) && !empty($_SESSION['userid']))
		{
			$this->obDb->query = "SELECT fMemberPoints FROM ".CUSTOMERS." WHERE iCustmerid_PK='".$_SESSION['userid']."'";
			$result = $this->obDb->fetchquery();
			if($this->obDb->record_count == 1 && $result[0]->fMemberPoints > 0)
			{
				$this->ObTpl->set_var('TPL_VAR_MP',$result[0]->fMemberPoints);
				$this->ObTpl->set_var('TPL_VAR_MPv',MPOINTVALUE);
				$this->ObTpl->parse("mpoint_use_blok","MPOINT_USE_BLK");
			}
		}
		$this->ObTpl->parse("mpoint_blok","MPOINT_BLK");
	}
	if(VAT_POSTAGE_FLAG==1)
	{
		$this->ObTpl->set_var("TPL_VAR_VAT_POSTAGE","1");
		$this->ObTpl->set_var("TPL_VAR_VAT_RATE",$_SESSION["VAT"]/100);
	}
	else
	{
		$this->ObTpl->set_var("TPL_VAR_VAT_POSTAGE","0");
		$this->ObTpl->set_var("TPL_VAR_VAT_RATE",$_SESSION["VAT"]/100);
	}
	if(isset($_SESSION['postagePrice']))
	{
		$this->ObTpl->set_var("TPL_VAR_SHIPTOTAL", number_format($_SESSION['postagePrice'],2));
	}
	else
	{
		$result = $this->calcPostage($country,$state);
		if(1 == $result)
		{
			if(isset($_SESSION['poptions']))
			{
				$this->ObTpl->set_var("TPL_VAR_SHIPTOTAL","0");
				$this->ObTpl->set_var("TPL_VAR_SHIP_OPTIONS",$_SESSION['poptions']);
				$this->ObTpl->parse("ship_options_blk","TPL_SHIP_OPTIONS_BLK");
			}
			else
			{
				$this->ObTpl->set_var("TPL_VAR_SHIPTOTAL",$_SESSION['pprice']);
			}
		}
		elseif(2 == $result)
		{
			$this->ObTpl->set_var("TPL_VAR_SHIPTOTAL","0");
			$this->ObTpl->set_var("TPL_VAR_SHIP_OPTIONS",$_SESSION['poptions']);
			$this->ObTpl->parse("ship_options_blk","TPL_SHIP_OPTIONS_BLK");
			//submit form to new ecom url takes shipping option and sets session, then redirects them to saveorder 
		}
		else
		{
			$this->ObTpl->set_var("TPL_VAR_SHIPTOTAL","0");
		}
		if(isset($_SESSION['poptions']))
		{
			unset($_SESSION['poptions']);
		}
	}
	$this->ObTpl->set_var("TPL_VAR_VATTOTAL", number_format($_SESSION['vatTotal'],2));
	$this->ObTpl->set_var("TPL_VAR_TOTAL", number_format($_SESSION['grandTotal'],2));
	return ($this->ObTpl->parse("return", "TPL_PAYPAL_EXPRESS_REVIEW_FILE"));
}

function DoExpressCheckout($orderid)
{
	$token = $_SESSION['ppxtoken'];
	//Finalize Transaction
	$paymentType = urlencode("Sale");			// or 'Sale' or 'Order'
	$paymentAmount = urlencode($_SESSION['grandTotal']);
	$currencyID = urlencode(PAYMENT_CURRENCY);						// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
	$payerID = $_SESSION['pppid'];
	// Add request-specific fields to the request string.

	$nvpStr2 = $_SESSION['nvpspx'];
	$nvpStr = "&TOKEN=$token&PAYERID=$payerID";
	if($_SESSION['newship'] == 1)
	{
		if(isset($_SESSION['mptotal']) && !empty($_SESSION['mptotal']))
		{
			$newamt2 = 0;
			$start = strpos($nvpStr2,"&PAYMENTREQUEST_0_ITEMAMT=");
			$end = strpos($nvpStr2,"&",$start + 2);
			$length = $end - $start;
			$old = substr($nvpStr2,$start+26,$length-26);
			$newamt = number_format(floatval($old) - floatval($_SESSION['memberPointsUsedAmount']),2);
			//die($newamt . " | ".floatval($old)." | ".floatval($_SESSION['memberPointsUsedAmount']));
			if($newamt <= 0)
			{
				$newamt = 0.01;
				$newamt2 = 0.01;
			}
			//die($old);
			$nvpStr2 = substr_replace($nvpStr2,"",$start,$length);
			$start = strpos($nvpStr2,"&PAYMENTREQUEST_0_PAYMENTACTION=Sale");
			$end = strpos($nvpStr2,"&",$start + 2);
			$length = $end - $start;
			$nvpStr3 = "&L_PAYMENTREQUEST_0_NAME".$_SESSION['nvpitemqty']."=".urlencode("MemberPoint Discount");
			$nvpStr3 = $nvpStr3 . "&L_PAYMENTREQUEST_0_DESC".$_SESSION['nvpitemqty']."=".urlencode($_SESSION['usedMemberPoints'] . " MemberPoints Used");
			$nvpStr3 = $nvpStr3 . "&L_PAYMENTREQUEST_0_AMT".$_SESSION['nvpitemqty']."=".urlencode(number_format(-$_SESSION['memberPointsUsedAmount'] + $newamt2,2));
			$nvpStr3 = $nvpStr3 . "&L_PAYMENTREQUEST_0_QTY".$_SESSION['nvpitemqty']."=1&PAYMENTREQUEST_0_PAYMENTACTION=Sale";
			$nvpStr2 = substr_replace($nvpStr2,$nvpStr3,$start,$length);
			$nvpStr = $nvpStr . "&PAYMENTREQUEST_0_ITEMAMT=".$newamt."&PAYMENTREQUEST_0_SHIPPINGAMT=" .number_format($_SESSION['postagePrice'],2);
		}
		else
		{
		$nvpStr = $nvpStr . "&PAYMENTREQUEST_0_SHIPPINGAMT=" .number_format($_SESSION['postagePrice'],2);
		}
	}
	if($_SESSION['newtax'] == 1)
	{
		$start = strpos($nvpStr2,"&PAYMENTREQUEST_0_TAXAMT=");
		$end = strpos($nvpStr2,"&",$start + 2);
		$length = $end - $start;
		$nvpStr2 = substr_replace($nvpStr2,"",$start,$length);
		$nvpStr = $nvpStr . "&PAYMENTREQUEST_0_TAXAMT=" . number_format($_SESSION['vatamt'],2);
	}
	if($_SESSION['newtotal'] == 1)
	{
		$start = strpos($nvpStr2,"&PAYMENTREQUEST_0_AMT=");
		$end = strpos($nvpStr2,"&",$start + 2);
		$length = $end - $start;
		$nvpStr2 = substr_replace($nvpStr2,"",$start,$length);
		if(isset($_SESSION['mptotal']) && !empty($_SESSION['mptotal']))
		{
			//die($_SESSION['nvpitemqty2']);
			if($newamt == 0.01)
			{
			$_SESSION['mptotal'] = $_SESSION['mptotal'] + 0.01;
			}
			$nvpStr = $nvpStr . "&PAYMENTREQUEST_0_AMT=" . number_format($_SESSION['mptotal'],2);
			unset($_SESSION['mptotal']);
		}
		else
		{
			$nvpStr = $nvpStr . "&PAYMENTREQUEST_0_AMT=" . number_format($_SESSION['grandTotal'],2);
		}
		//unset($_SESSION['nvpitemqty']);
	}
	unset($_SESSION['newship']);
	unset($_SESSION['newtax']);
	unset($_SESSION['newtotal']);






	$nvpStr = $nvpStr2.$nvpStr."&PAYMENTREQUEST_0_INVNUM=".$_SESSION['invpxx'];























	unset($_SESSION['invpxx']);




















	//die($nvpStr2."<br/><br/>after:<br/>".$nvpStr);
	//die($nvpStr);
	// Execute the API operation; see the PPHttpPost function above.
	$httpParsedResponseAr = $this->PPHttpPost('DoExpressCheckoutPayment', $nvpStr);
	//die(print_r($httpParsedResponseAr,true));
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
		//Update Database
		$this->obDb->query = "UPDATE ".ORDERS." SET iPayStatus=1,vSessionId='".SESSIONID."',iOrderStatus=1 WHERE iOrderid_PK=".$orderid;
		$row=$this->obDb->updateQuery();
		//echo "test";
		unset($_SESSION['ppxtoken']);
		$retUrl=SITE_SAFEURL."ecom/index.php?action=checkout.process&mode=".$orderid;
		$this->libFunc->m_mosRedirect($retUrl);
	} else  {
		//redirect to billship w/ error
		$_SESSION['cardsave_error'] = "A Error has occured while communicating this transaction with Paypal Express Servers, please checkout using our Checkout instead. Paypal as a payment method may or may not work.";
		die(print_r($httpParsedResponseAr,true));
		error_log(print_r($httpParsedResponseAr,true),3,SITE_PATH."plugins/Paypal Express Checkout/error_log");
		$retUrl=SITE_SAFEURL."ecom/index.php?action=checkout.billing";
		$this->libFunc->m_mosRedirect($retUrl);
	}
}

function m_postagePrice($total,$itemqty,$postageqty)
	{
		$this->obDb->query ="SELECT vField1,vField2,vField3,fBaseRate FROM  ".POSTAGE.",".POSTAGEDETAILS." WHERE iPostId_PK=iPostId_FK AND vKey='".DEFAULT_POSTAGE_METHOD."'";
		$rsPostage=$this->obDb->fetchQuery();
		$rsCount=$this->obDb->record_count;

		if(DEFAULT_POSTAGE_METHOD=='flat')
		{
			return $rsPostage[0]->vField1;
		}#END FLAT
		elseif(DEFAULT_POSTAGE_METHOD=='percent')
		{
			$postPrice=($rsPostage[0]->vField1*$total)/100;
			if($rsPostage[0]->fBaseRate>$postPrice)
			{
				return $rsPostage[0]->fBaseRate;
			}
			else
			{
				return $postPrice;
			}
		}#END PERCENT
		elseif(DEFAULT_POSTAGE_METHOD=='range')
		{

			for($i=0;$i<$rsCount;$i++)
			{
				#IF POSTAGE IS UNLIMITED
				if($rsPostage[$i]->vField1<=$total && $rsPostage[$i]->vField2=='0')
				{
					return $rsPostage[$i]->vField3;
				}
				#CHECKING RANGES
				if($rsPostage[$i]->vField1<=$total && $rsPostage[$i]->vField2>=$total)
				{
					return $rsPostage[$i]->vField3;
				}
			}	#ENF FOR LOOP
			
		}#END RANGE
		elseif(DEFAULT_POSTAGE_METHOD=='peritem') {
				return $rsPostage[0]->vField1+$rsPostage[0]->vField2*($itemqty-$postageqty-1);
		}#END PERITEM
		elseif(DEFAULT_POSTAGE_METHOD=='codes')
		{
			return $rsPostage[0]->fBaseRate;			
		}#END PERITEM
		else
		{
			return 0;
		}

	}#END POSTAGE CALCULATION METHOD
	
	function calcPostage($paypalcountryid,$paypalstateid)
	{
		//attempt to calculate country and state id from retuned values and get postage
			$countryid = "";
			$stateid = "";
			$countryid = $this->getCountryId($paypalcountryid);
			if($countryid > -1)
			{
				$stateid = $this->getStateId($paypalstateid,$countryid);
				if($stateid > -1)
				{
					$price = $this->comFunc->m_recalculate_postage($countryid,$stateid);
					$specialprice = floatval($price[1]);
					$price = floatval($price[0]);
				}
				else
				{
					//no state id
					$price = $this->comFunc->m_recalculate_postage($countryid);
					$specialprice = floatval($price[1]);
					$price = floatval($price[0]);
				}
			}
			elseif(SPECIAL_POSTAGE == 1)
			{
				$price = $this->m_postagePrice($_SESSION['itemAmt'],$_SESSION['itemqty'],$_SESSION['postageqty']) + $_SESSION['cartWeightPrice'];
			}
			else
			{
				//no country id so this calc is impossible
				//redirect to TE checkout with generic error or return 0?
				//log in a error_log
				die("no shipping?");
				return 0;
			}
		//check for special postage options
			if(SPECIAL_POSTAGE == 1)
			{
				$this->obDb->query = "SELECT * FROM ".POSTAGEDETAILS." WHERE iPostId_FK=6 ORDER BY `iPostDescId_PK` ASC";
				$result = $this->obDb->fetchquery();
				$row_count = $this->obDb->record_count;
				$tempstring = "<table><tr><td class='first'>"/*$tempstring . "<input type='hidden' id='opval0' value='".$price."'/><input type='radio' name='oid' id='postage0' value='0'/><label>".DEFAULT_POSTAGE_NAME." ".CONST_CURRENCY.$price."</label>"*/;
				foreach($result as $key => $value)
				{
					$temp = ($_SESSION['postageqty']-1) * intval($result[$key]->vField2) + $result[$key]->vField1;
					$pprice = $price + $temp;
					$tempstring = $tempstring . "<input type='hidden' id='opval".$result[$key]->iPostDescId_PK."' value='".$pprice."'/><input type='radio' name='oid' id='postage".$key."' value='".$result[$key]->iPostDescId_PK."'/><label>".$result[$key]->vDescription." <span class='orange'>".CONST_CURRENCY.$pprice."</span></label><br/>";
				}
				$tempstring = $tempstring . "</td></tr></table>";
				$_SESSION['poptions'] = $tempstring;
				$_SESSION['basep'] = $price;
				return 2;
			}
			else
			{
				$_SESSION['pprice'] = $price + $_SESSION['cartWeightPrice'];
				if(isset($specialprice))
				{
					$_SESSION['spprice'] = $specialprice + $_SESSION['cartWeightPrice'];
					$_SESSION['specdel'] = Array($_SESSION['pprice'],$_SESSION['spprice']);
					$_SESSION['poptions'] = "<input type='hidden' id='opvala' value='".$_SESSION['pprice']."'/><input type='radio' name='oid' id='postagea' value='a'/><label>".DEFAULT_POSTAGE_NAME." ".CONST_CURRENCY.$_SESSION['pprice']."</label>"."<input type='hidden' id='opvalb' value='".$_SESSION['spprice']."'/><input type='radio' name='oid' id='postageb' value='b'/><label>Special Delivery ".CONST_CURRENCY.$_SESSION['spprice']."</label>";
				}
				return 1;
			}
	}
	
	function getCountryId($country)
	{
		$this->obDb->query = "SELECT iCountryId_PK FROM ".COUNTRY." WHERE vShortName='".$country."'";
		$result = $this->obDb->fetchquery();
		$row_count = $this->obDb->record_count;
		if($row_count == 1)
		{
			$countryid = $result[0]->iCountryId_PK;
			return $countryid;
		}
		else
		{
			return -1;
		}
	}
	
	function getStateId($state,$country)
	{
		$this->obDb->query = "SELECT iStateId_PK FROM ".STATES." WHERE vStateName='".$state."' AND iCountryID_FK='".$country."'";
		$result = $this->obDb->fetchquery();
		$row_count = $this->obDb->record_count;
		if($row_count == 1)
		{
			$stateid = $result[0]->iStateId_PK;
			return $stateid;
		}
		else
		{
			return -1;
		}
	}
	
	function calcSetVat($country,$state = "")
	{
		//die($country);
		if(!empty($state))
		{
			$this->obDb->query = "SELECT fTax FROM ".STATES." WHERE iStateId_PK='".$state."'";
			$result = $this->obDb->fetchquery();
			if($this->obDb->record_count == 1)
			{
				$_SESSION['VAT'] = $result[0]->fTax;
			}
			else
			{
				$state = "";
			}
		}
		if(empty($state))
		{
			$this->obDb->query = "SELECT fTax FROM ".COUNTRY." WHERE iCountryId_PK='".$country."'";
			$result = $this->obDb->fetchquery();
			if($this->obDb->record_count == 1)
			{
				$_SESSION['VAT'] = $result[0]->fTax;
			}
			else
			{
				$_SESSION['VAT'] = DEFAULTVATTAX;
			}
		}
		return true;
	}
}
?>
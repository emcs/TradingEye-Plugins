<?php

class MoneyBookers
{
	function MoneyBookers()
	{
		$this->gateway = "https://www.moneybookers.com/app/payment.pl";
		$this->merchant = SKRILL_MERCHANT;
		$this->secret = SKRILL_SECRET;
	}

	function SendToGateway($data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->gateway);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec ($ch);
		curl_close ($ch);
		//die($result);
		$s = stripos($result,"Set-Cookie: SESSION_ID=") + 23;
		$l = stripos($result,";",$s) - $s;
		$sid = substr($result,$s,$l);
		//die("SID:".$sid."\n\n\n<br/><BR/>".$result);
		$retUrl=$this->gateway."?sid=".$sid;
		$this->libFunc->m_mosRedirect($retUrl);
	}

	function Callback()
	{
		error_log("started callback"."\n",3,SITE_PATH."moneybookers.log");
		if($this->verifySignature())
		{
			//error_log("passed callback"."\n",3,SITE_PATH."moneybookers.log");
			//error_log("before update\n",3,SITE_PATH."moneybookers.log");
			$this->obDb->query= "UPDATE ".ORDERS." SET iOrderStatus=1,iPayStatus=1,vSessionId='".SESSIONID."' WHERE iOrderid_PK = '".$_POST['transaction_id']."'";
			$this->obDb->updateQuery();
			//error_log("after update\n",3,SITE_PATH."moneybookers.log");
			$obreceipt->m_sendOrderDetails($_POST['transaction_id']);
			echo "ok/200";
		}
		else
		{
			error_log("failed callback,".$_SERVER['REMOTE_ADDR']."\n",3,SITE_PATH."moneybookers.log");
			die();
		}
	}

	function verifySignature()
	{
		$toconcat = $_POST['merchant_id'].$_POST['transaction_id'].strtoupper(md5($this->secret)).$_POST['mb_amount'].$_POST['mb_currency'].$_POST['status'];
		if (strtoupper(md5($toconcat)) == $_POST['md5sig'] && $_POST['status'] == 2 && $_POST['pay_to_email'] == $this->merchant)
		{
			$this->obDb->query="SELECT fTotalPrice FROM ".ORDERS." WHERE iOrderid_PK='".$_POST['transaction_id']."'";
			$result = $this->obDb->fetchQuery();
			if($result[0]->fTotalPrice == $_POST['mb_amount'])
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return 0;
		}
	}

	function createData($orderid,$lang,$total,$currency,$c_email,$c_fname,$c_lname,$c_addr,$c_addr2,$c_phone,$c_zip,$c_city,$c_state,$c_coutry)
	{
		$data = "pay_to_email=".urlencode($this->merchant);
		$data = $data."&recipient_description=".urlencode(SITE_NAME);
		$data = $data."&transaction_id=".$orderid;
		$data = $data."&return_url=".urlencode(SITE_SAFEURL."ecom/index.php?action=checkout.receipt&mode=".$orderid);
		//$data = $data."&return_url_text=View Receipt";
		$data = $data."&cancel_url=".urlencode(SITE_SAFEURL."ecom/index.php?action=ecom.viewcart");
		$data = $data."&status_url=".urlencode(SITE_SAFEURL."ecom/index.php?action=checkout.skrill");
		$data = $data."&language=".$lang;
		$data = $data."&hide_login=1";
		//EN, DE, ES, FR, IT, PL, GR RO, RU, TR, CN, CZ, NL, DA, SV or FI.
		$data = $data."&pay_from_email=".urlencode($c_email);
		$data = $data."&firstname=".urlencode($c_fname);
		$data = $data."&lastname=".urlencode($c_lname);
		$data = $data."&address=".urlencode($c_addr);
		$data = $data."&address2=".urlencode($c_addr2);
		$data = $data."&phone_number=".urlencode($c_phone);
		$data = $data."&postal_code=".urlencode($c_zip);
		$data = $data."&city=".urlencode($c_city);
		$data = $data."&state=".urlencode($c_state);
		$data = $data."&country=".urlencode($c_coutry);
		$data = $data."&amount=".urlencode($total);
		$data = $data."&currency=".urlencode($currency);
		$data = $data."&prepare_only=1";
		$data = $data."&merchant_fields=platform";
		$data = $data."&platform=38335104";
		if(SKRILL_PMETHODS != "")
		{
			$data = $data."&payment_methods=".urlencode(SKRILL_PMETHODS);
		}
		//die($data);
		return $data;
	}
}
?>
<?php
$obDatabase = new database();
$obDatabase->db_host = DATABASE_HOST;
$obDatabase->db_user = DATABASE_USERNAME;
$obDatabase->db_password = DATABASE_PASSWORD;
$obDatabase->db_port = DATABASE_PORT;
$obDatabase->db_name = DATABASE_NAME;
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentSenseID'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentSenseID','','PS_MERCHANT_ID','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentSensePass'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentSensePass','','PS_MERCHANT_PASS','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentSenseURL'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentSenseURL','paymentsensegateway.com','PS_GATEWAY_DOMAIN','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentSensePORT'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentSensePORT','4430','PS_GATEWAY_PORT','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentSenseKey'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentSenseKey','','PS_SECRET_KEY','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentSenseCurrency'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentSenseCurrency','826','PS_CURRENCY','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseMerchantID'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentRSenseMerchantID','','PSr_MERCHANT_ID','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSensePassword'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentRSensePassword','','PSr_MERCHANT_PASS','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseKey'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentRSenseKey','','PSr_KEY','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseDomain'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentRSenseDomain','paymentsensegateway.com','PSr_DOMAIN','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseResults'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentRSenseResults','0','PSr_RESULTS_DISPLAY','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseCV2'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentRSenseCV2','1','PSr_CV2_MANDATORY','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseCurrency'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('PaymentRSenseCurrency','826','PSr_CURRENCY','1')";
	$result = $obDatabase->updateQuery();
}
?>
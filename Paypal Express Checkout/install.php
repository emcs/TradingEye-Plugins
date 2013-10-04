<?php
$obDatabase = new database();
$obDatabase->db_host = DATABASE_HOST;
$obDatabase->db_user = DATABASE_USERNAME;
$obDatabase->db_password = DATABASE_PASSWORD;
$obDatabase->db_port = DATABASE_PORT;
$obDatabase->db_name = DATABASE_NAME;
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='paypalx'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('paypalx','0','PAYPAL_EXPRESS','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='paypalxapiuser'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('paypalxapiuser','','PAYPAL_APIID','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='paypalxpal'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('paypalxpal','','PAYPAL_PALID','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='paypalxsig'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('paypalxsig','','PAYPAL_SIG','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='paypalxpass'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('paypalxpass','','PAYPAL_PASS','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='paypalxauto'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('paypalxauto','0','PAYPAL_EXPRESS_AUTO','1')";
	$result = $obDatabase->updateQuery();
}
?>
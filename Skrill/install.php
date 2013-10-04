<?php
$obDatabase = new database();
$obDatabase->db_host = DATABASE_HOST;
$obDatabase->db_user = DATABASE_USERNAME;
$obDatabase->db_password = DATABASE_PASSWORD;
$obDatabase->db_port = DATABASE_PORT;
$obDatabase->db_name = DATABASE_NAME;
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='skrill'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('skrill','0','SKRILL_ONOFF','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='skrillmerchant'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('skrillmerchant','','SKRILL_MERCHANT','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='skrillsecret'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('skrillsecret','','SKRILL_SECRET','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='skrillcurrency'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('skrillcurrency','','SKRILL_CURRENCY','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='skrilllang'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('skrilllang','','SKRILL_LANG','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='skrillpmethod'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('skrillpmethod','','SKRILL_PMETHODS','1')";
	$result = $obDatabase->updateQuery();
}
?>
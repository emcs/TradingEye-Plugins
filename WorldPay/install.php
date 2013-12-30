<?php
$obDatabase = new database();
$obDatabase->db_host = DATABASE_HOST;
$obDatabase->db_user = DATABASE_USERNAME;
$obDatabase->db_password = DATABASE_PASSWORD;
$obDatabase->db_port = DATABASE_PORT;
$obDatabase->db_name = DATABASE_NAME;

$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='WorldPayMercCode'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('WorldPayMercCode','','WorldPayMerchantCode','1')";
	$result = $obDatabase->updateQuery();
}

$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='WorldPayPass'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('WorldPayPass','','WorldPayPass','1')";
	$result = $obDatabase->updateQuery();
}

$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='WorldPayInstall'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('WorldPayInstall','','WorldPayInstallationId','1')";
	$result = $obDatabase->updateQuery();
}

$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='WorldPayCurrencyCode'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('WorldPayCurrencyCode','GBP','WorldPayCurrencyCode','1')";
	$result = $obDatabase->updateQuery();
}

$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='WorldPayHMID'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('WorldPayHMID','','WorldPayRedirectInstallationId','1')";
	$result = $obDatabase->updateQuery();
}

$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='WorldPayHCUR'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,iAdminUser) VALUES('WorldPayHCUR','GBP','WorldPayRedirectCurrencyCode','1')";
	$result = $obDatabase->updateQuery();
}
?>
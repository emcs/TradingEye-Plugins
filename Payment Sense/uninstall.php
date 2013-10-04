<?php
$obDatabase = new database();
$obDatabase->db_host = DATABASE_HOST;
$obDatabase->db_user = DATABASE_USERNAME;
$obDatabase->db_password = DATABASE_PASSWORD;
$obDatabase->db_port = DATABASE_PORT;
$obDatabase->db_name = DATABASE_NAME;
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentSenseID'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentSenseID'";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentSensePass'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentSensePass'";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentSenseURL'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentSenseURL'";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentSensePORT'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentSensePORT'";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentSenseKey'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentSenseKey'";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentSenseCurrency'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentSenseCurrency'";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseMerchantID'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseMerchantID'";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSensePassword'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSensePassword'";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseKey'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseKey'";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseDomain'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseDomain'";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseResults'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseResults'";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseCV2'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseCV2'";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseCurrency'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 1)
{
	$obDatabase->query = "DELETE FROM ".SITESETTINGS." WHERE vDatatype='PaymentRSenseCurrency'";
	$result = $obDatabase->updateQuery();
}
?>
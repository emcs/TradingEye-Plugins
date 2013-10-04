<?php
$obDatabase = new database();
$obDatabase->db_host = DATABASE_HOST;
$obDatabase->db_user = DATABASE_USERNAME;
$obDatabase->db_password = DATABASE_PASSWORD;
$obDatabase->db_port = DATABASE_PORT;
$obDatabase->db_name = DATABASE_NAME;
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='MobiCartEnabled'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,default_value,iAdminUser) VALUES('MobiCartEnabled','disabled','MOBICARTST','disabled','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='MobiCartAPI'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,default_value,iAdminUser) VALUES('MobiCartAPI',null,'MOBICARTAPI','','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='MobiCartUser'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,default_value,iAdminUser) VALUES('MobiCartUser',null,'MOBICARTUSR','','1')";
	$result = $obDatabase->updateQuery();
}
$obDatabase->query = "SELECT vSmalltext FROM ".SITESETTINGS." WHERE vDatatype='MobiCartStore'";
	$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "INSERT INTO ".SITESETTINGS." (vDatatype,vSmalltext,sConstantName,default_value,iAdminUser) VALUES('MobiCartStore',null,'MOBICARTSID','','1')";
	$result = $obDatabase->updateQuery();
}
?>
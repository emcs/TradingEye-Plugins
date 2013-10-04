<?php
$obDatabase = new database();
$obDatabase->db_host = DATABASE_HOST;
$obDatabase->db_user = DATABASE_USERNAME;
$obDatabase->db_password = DATABASE_PASSWORD;
$obDatabase->db_port = DATABASE_PORT;
$obDatabase->db_name = DATABASE_NAME;
$obDatabase->query = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '".DATABASE_NAME."' AND TABLE_NAME = '".PRODUCTS."' AND COLUMN_NAME = 'tabsflag'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "ALTER table ".PRODUCTS." ADD COLUMN tabsflag INT NOT NULL DEFAULT 0;";
	$obDatabase->updateQuery();
}
$obDatabase->query = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '".DATABASE_NAME."' AND TABLE_NAME = '".PRODUCTS."' AND COLUMN_NAME = 'tabpanels'";
$result = $obDatabase->fetchQuery();
if($obDatabase->record_count == 0)
{
	$obDatabase->query = "ALTER table ".PRODUCTS." ADD COLUMN tabpanels longtext NOT NULL default ''";
	$obDatabase->updateQuery();
}
?>
<?php
/*

	Banner manager plug-in
	Developed by Jim Hill @ Wiseguy Digital
	http://www.wiseguydigital.com

*/

include_once("classes/admin/bannerManagerAdmin.php");
include_once("bannerConfig.php");

$BM = new bannerManagerAdmin();

// Set the template
$BM->trackBanner();

?>
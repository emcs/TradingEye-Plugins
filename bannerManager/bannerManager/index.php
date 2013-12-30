<?php
/*

	Banner manager plug-in
	Developed by Jim Hill @ Wiseguy Digital
	http://www.wiseguydigital.com

*/

error_reporting(E_ALL);

include_once("classes/admin/bannerManagerAdmin.php");
include_once("bannerConfig.php");

$BM = new bannerManagerAdmin();

// Set the template
$BM->m_setTempate();

// INSTALLATION CHECKS

// Does the banner image directory exist?
if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $bannerConfig['IMAGE-DIR']))
{
	$BM->obMainTemplate->set_var("TPL_VAR_BODY",$BM->installError('no_dir'));
	$BM->m_parseTemplate();
	exit;
}

// Is the directory writeable?
if (!is_writable($_SERVER['DOCUMENT_ROOT'] . $bannerConfig['IMAGE-DIR']))
{
	$BM->obMainTemplate->set_var("TPL_VAR_BODY",$BM->installError('not_writeable'));
	$BM->m_parseTemplate();
	exit;
}

// Create the table from the GET variable
if(isset($_GET['addDb']))
{
	if (!$BM->createDBTable(BANNERS))
	{
		$BM->obMainTemplate->set_var("TPL_VAR_BODY", $BM->installError('create_table'));
		$BM->m_parseTemplate();
		exit;
	}
}

// Does the banners table exist in the database?
if (!$BM->checkDBTable(BANNERS))
{
	$BM->obMainTemplate->set_var("TPL_VAR_BODY",$BM->installError('no_db_table'));
	$BM->m_parseTemplate();
	exit;
}

// Check that the placeholders have been set in the config file
if (count($bannerConfig['BANNER-PLACEHOLDERS']) == 0)
{
	$BM->obMainTemplate->set_var("TPL_VAR_BODY",$BM->installError('no_placeholders'));
	$BM->m_parseTemplate();
	exit;
}

// MAIN MANAGER

// Set the correct page
if (isset($_GET['mode'])) 
{
	switch ($_GET['mode'])
	{
	
		case 'add':
			$BM->obMainTemplate->set_var("TPL_VAR_BODY",$BM->addEditBanner());
			break;
			
		case 'edit':
			$BM->obMainTemplate->set_var("TPL_VAR_BODY",$BM->addEditBanner());
			break;
			
		case 'delete':
			$BM->obMainTemplate->set_var("TPL_VAR_BODY",$BM->deleteBanner());
			break;
			
		case 'help':
			$BM->obMainTemplate->set_var("TPL_VAR_HELPBODY",$BM->showHelp());
			break;
			
		case 'viewSWF':
			$BM->obMainTemplate->set_var("TPL_VAR_BODY",$BM->viewSWF());
			break;
			
		default:
			$BM->obMainTemplate->set_var("TPL_VAR_BODY",$BM->showOverview());		
	
	}
} else {
	$BM->obMainTemplate->set_var("TPL_VAR_BODY",$BM->showOverview());
}

// Parse the template
$BM->m_parseTemplate();


?>
<?php
/*

	Banner Manager Trading Eye plug-in 
	v1.0
	
	Developed by Jim Hill @ Wiseguy Digital
	http://www.wiseguydigital.com
	06 February 2008

*/

include_once("../plugins_common.php");

class bannerManagerAdmin extends c_commonPlugin {

	// Set a template debug level for this class
	var $debugSetting = 0;
	
	#FUNCTION TO SHOW THE BANNER OVERVIEW
	function showOverview()
	{
	
		global $bannerConfig;
	
		$this->ObTpl = new template();
		$this->ObTpl->debug = $this->debugSetting;
		$this->ObTpl->set_file("bannerManager", 'templates/bannerManager.tpl.htm');
		
		// Initialise the template blocks and sub-blocks
		$this->ObTpl->set_block("bannerManager","TPL_OVERVIEW_BLK","overviewTable");
		$this->ObTpl->set_block("TPL_OVERVIEW_BLK","TPL_BANNERS_BLK","bannerDetails");
		$this->ObTpl->set_block("bannerManager","TPL_ADD_EDIT_BLK","blank");
		$this->ObTpl->set_block("bannerManager","VIEW_SWF_BLK","blank");
		$this->ObTpl->set_var("blank","");
		
		// Run through the available campaigns
		$result = $this->obDb->execQry("SELECT * FROM ".BANNERS ." ORDER BY tmAddedDate DESC");
		$num_rows = mysql_num_rows($result);
		if($num_rows > 0)
		{
			while ($row = mysql_fetch_array($result))
			{
				$this->ObTpl->set_var("BANNER_CAMPAIGN_NAME", $row['vCampaignName']);
				// Show correct link for images and swfs
				if ($row['vFileType'] == 'image')
				{
					$fileLink = '<a href="'.$bannerConfig['IMAGE-DIR'] . $row['vFile'].'" rel="lightbox">View image</a>';
				} else {
					$fileLink = '<a href="?mode=viewSWF&amp;bannerID='. $row['iBannerid_PK'].'">View swf</a>';
				}
				$this->ObTpl->set_var("BANNER_FILE_LINK", $fileLink);
				// Display page
				$displayPage = ($row['vDisplayPage'] != '') ? '<a href="'.$row['vDisplayPage'].'">View page</a>' : 'All';
				$this->ObTpl->set_var("BANNER_DISPLAY_PAGE", $displayPage);
				$this->ObTpl->set_var("BANNER_NUM_VIEWS", $row['iPageDisplays']);
				$this->ObTpl->set_var("BANNER_NUM_CLICKS", $row['iNumClicks']);
				@$this->ObTpl->set_var("BANNER_CTR", number_format((($row['iNumClicks'] / $row['iPageDisplays'])*100),2));
				$this->ObTpl->set_var("BANNER_IS_ACTIVE", $row['iIsActive']);
				$this->ObTpl->set_var("BANNER_ID", $row['iBannerid_PK']);
				$this->ObTpl->parse("bannerDetails", "TPL_BANNERS_BLK", true);
			}
		} else {
			$this->ObTpl->parse("bannerDetails", "blank", true);
		}
		
		// Parse the main table
		$this->ObTpl->parse("overviewTable", "TPL_OVERVIEW_BLK", true);

		// Define the standard settings		
		$this->ObTpl->set_var("TPL_VAR_SITEURL",SITE_URL);
		$this->ObTpl->set_var("TPL_VAR_SITENAME",SITE_NAME);
		$this->ObTpl->set_var("GRAPHICSMAINPATH",SITE_URL."graphics/");
		
		return($this->ObTpl->parse("return","bannerManager"));
	
	}

	#FUNCTION TO ADD / EDIT A BANNER
	function addEditBanner()
	{
	
		global $bannerConfig;
		
		// Initialise Display
		$this->ObTpl = new template();
		$this->ObTpl->debug = $this->debugSetting;
		$this->ObTpl->set_file("bannerManager", 'templates/bannerManager.tpl.htm');
		
		// Initialise the template blocks and sub-blocks
		$this->ObTpl->set_block("bannerManager","TPL_OVERVIEW_BLK","blank");
		$this->ObTpl->set_block("bannerManager","VIEW_SWF_BLK","blank");
		$this->ObTpl->set_block("bannerManager","TPL_ADD_EDIT_BLK","addEditTable");
		$this->ObTpl->set_block("TPL_ADD_EDIT_BLK","TPL_ERROR_BLK","errors");
		$this->ObTpl->set_block("TPL_ADD_EDIT_BLK","TPL_PLACEHOLDERS_BLK","bannerPlaces");
		$this->ObTpl->set_var("errors","");
		$this->ObTpl->set_var("blank","");
		$this->ObTpl->set_var("EDIT_SHOW_CURRENT_FILE", '');
		$mode = ($_GET['mode'] == 'edit') ? $_GET['mode'] . '&amp;bannerID=' . $_GET['bannerID'] : $_GET['mode'];
		$this->ObTpl->set_var("MODE_FORM", $mode);
		$this->ObTpl->set_var("MODE_CAPS",ucfirst($_GET['mode']));
		
		// Deal with submitted forms
		if (isset($_POST) && isset($_POST['submitted']))
		{
			// Set up the error array
			$errors = array();
			
			// ADD A NEW BANNER
			if ($_GET['mode'] == 'add')
			{
			
				// Check that the fields have been filled in
				if (isset($_POST['campaignName']) && trim($_POST['campaignName']) == '') $errors[] = "You must enter a campaign name";
				if (!$_FILES['bannerFile']['tmp_name']) $errors[] = "You must enter add a file";
				if (isset($_POST['bannerPos']) && trim($_POST['bannerPos']) == '') $errors[] = "You must enter a banner position";
				if (isset($_POST['linkURL']) && trim($_POST['linkURL']) == '') $errors[] = "You must enter a link URL";
				if (isset($_POST['bannerType']) && trim($_POST['bannerType']) == '') $errors[] = "You must enter a banner type";
				if (isset($_POST['bannerWidth']) && trim($_POST['bannerWidth']) == '') $errors[] = "You must enter a banner width";
				if (isset($_POST['bannerHeight']) && trim($_POST['bannerHeight']) == '') $errors[] = "You must enter a banner height";
				
				// Print out any errors
				if (count($errors) > 0)
				{
					$errorStr = '<ul style="color:red;">';
					foreach ($errors as $error) $errorStr .= '<li>'.$error.'</li>';
					$errorStr .= '</ul>';
					$this->ObTpl->set_var("ERROR_TEXT", $errorStr);
					$this->ObTpl->parse("errors", "TPL_ERROR_BLK", true);
				} else {
			
					// Upload the banner file
					$upload_dir = $_SERVER['DOCUMENT_ROOT'].$bannerConfig['IMAGE-DIR'];
					$fileUpload = new FileUpload();
					$fileUpload->source = $_FILES["bannerFile"]["tmp_name"];
					$fileUpload->target = $upload_dir.$_FILES["bannerFile"]["name"];
					$newName = $fileUpload->upload();
					if($newName != false)
					{
						$bannerActive = (isset($_POST['bannerActive'])) ? 1 : 0;
						// Add the details to the database
						$this->obDb->query="INSERT INTO ".BANNERS."
							(`iBannerid_PK`,
							`vCampaignName`, 
							`vFile`,
							`vFileType`,
							`vBannerPos`,
							`iWidth`,
							`iHeight`,
							`vLinkURL`,
							`vDisplayPage`,
							`iIsActive`,
							`tmAddedDate`) 
						VALUES('',
							'".$_POST['campaignName']."',
							'".$newName."',
							'".$_POST['bannerType']."',
							'".$_POST['bannerPos']."',
							'".$_POST['bannerWidth']."',
							'".$_POST['bannerHeight']."',
							'".$_POST['linkURL']."',
							'".$_POST['displayPage']."',
							'".$bannerActive."',
							'".time()."')";
						$this->obDb->execQry($this->obDb->query);
						$subObjId=mysql_insert_id();
						
						// Forward to the summary page
						header("Location: /plugins/bannerManager/");
						
						return $this->ObTpl->parse("addEditTable", "TPL_ADD_EDIT_BLK", true);
						return($this->ObTpl->parse("return","bannerManager"));
						
					} else {
						return $this->returnError('There was a problem with the upload of the banner file to: ' . $upload_dir.$cleanName);
					}
				}
				
			}
			
			// EDIT
			if ($_GET['mode'] == 'edit')
			{
			
				// Check that the fields have been filled in
				if (isset($_POST['campaignName']) && trim($_POST['campaignName']) == '') $errors[] = "You must enter a campaign name";
				if (isset($_POST['bannerPos']) && trim($_POST['bannerPos']) == '') $errors[] = "You must enter a banner position";
				if (isset($_POST['linkURL']) && trim($_POST['linkURL']) == '') $errors[] = "You must enter a link URL";
				if (isset($_POST['bannerType']) && trim($_POST['bannerType']) == '') $errors[] = "You must enter a banner type";
				if (isset($_POST['bannerWidth']) && trim($_POST['bannerWidth']) == '') $errors[] = "You must enter a banner width";
				if (isset($_POST['bannerHeight']) && trim($_POST['bannerHeight']) == '') $errors[] = "You must enter a banner height";
				
				// Print out any errors
				if (count($errors) > 0)
				{
					$errorStr = '<ul style="color:red;">';
					foreach ($errors as $error) $errorStr .= '<li>'.$error.'</li>';
					$errorStr .= '</ul>';
					$this->ObTpl->set_var("ERROR_TEXT", $errorStr);
					$this->ObTpl->parse("errors", "TPL_ERROR_BLK", true);
				} else {
			
					// Upload the banner file if there is a new one
					if (isset($_FILES['bannerFile']) && $_FILES['bannerFile']["tmp_name"] != '')
					{
						$upload_dir = $_SERVER['DOCUMENT_ROOT'].$bannerConfig['IMAGE-DIR'];
						$fileUpload = new FileUpload();
						$fileUpload->source = $_FILES["bannerFile"]["tmp_name"];
						$fileUpload->target = $upload_dir.$_FILES["bannerFile"]["name"];
						$newName = $fileUpload->upload();
						if($newName != false) 
						{
							// Delete the old image
							$result = $this->obDb->execQry("SELECT vFile FROM ".BANNERS ." WHERE iBannerid_PK='".$_GET['bannerID']."' LIMIT 0,1");
							$num_rows = mysql_num_rows($result);
							if($num_rows > 0)
							{
								$row = mysql_fetch_array($result);
								// If the file exists then delete it
								if (file_exists($_SERVER['DOCUMENT_ROOT'].$bannerConfig['IMAGE-DIR'].$row['vFile']))
								{
									@unlink($_SERVER['DOCUMENT_ROOT'].$bannerConfig['IMAGE-DIR'].$row['vFile']);
								}
							}
						} else {
							return $this->returnError('There was a problem with the upload of the banner file to: ' . $upload_dir.$cleanName);
						}
					}
					
					// Update the database
					$bannerActive = (isset($_POST['bannerActive'])) ? 1 : 0;
					
					// Add the details to the database
					$fileDetails = (isset($newName)) ? $newName : $_POST['currentFile'];
					$query = "UPDATE ".BANNERS." SET
						vCampaignName = '".$_POST['campaignName']."',
						vFile = '".$fileDetails."',
						vfileType = '".$_POST['bannerType']."',
						vBannerPos = '".$_POST['bannerPos']."',
						iWidth = '".$_POST['bannerWidth']."',
						iHeight = '".$_POST['bannerHeight']."',
						vLinkURL = '".$_POST['linkURL']."',
						vDisplayPage = '".$_POST['displayPage']."',
						iIsActive = '".$bannerActive."'
					WHERE
						iBannerid_PK = '".$_GET['bannerID']."'";
					$this->obDb->query=$query;
					$this->obDb->updateQuery();
					
					// Forward to the summary page
					header("Location: /plugins/bannerManager/");
					
					return $this->ObTpl->parse("addEditTable", "TPL_ADD_EDIT_BLK", true);
					return($this->ObTpl->parse("return","bannerManager"));
					
				}
				
			}
			
		}
		
		// For editing banners, retreive the default values from the database if form has not been submitted
		if (($_GET['mode']=="edit") && (!isset($_POST['submitted'])))
		{
	
			// Get the banner ID
			if (!isset($_GET['bannerID'])) return $this->returnError('A banner ID has not been specified');
			
			$result = $this->obDb->execQry("SELECT * FROM ".BANNERS ." WHERE iBannerid_PK='".$_GET['bannerID']."' LIMIT 0,1");
			$num_rows = mysql_num_rows($result);
			if($num_rows > 0)
			{
				$row = mysql_fetch_array($result);
				$_POST['campaignName'] = $row['vCampaignName'];
				$_POST['bannerPos'] = $row['vBannerPos'];
				$_POST['linkURL'] = $row['vLinkURL'];
				$_POST['bannerType'] = $row['vFileType'];
				$_POST['bannerWidth'] = $row['iWidth'];
				$_POST['bannerHeight'] = $row['iHeight'];
				$_POST['displayPage'] = $row['vDisplayPage'];
				$_POST['bannerActive'] = ($row['iIsActive'] == 1) ? true : false;
				$this->ObTpl->set_var("EDIT_SHOW_CURRENT_FILE", '<input type="hidden" name="currentFile" id="currentFile" value="'.$row['vFile'].'" /><p>Current banner file: <a href="'.$bannerConfig['IMAGE-DIR'].$row['vFile'].'" rel="lightbox">'.$row['vFile'].'</a></p><p><em>You can use the field below to replace the file:</em></p>');
			}
		}
		
		// Do we accept Flash?
		if ($bannerConfig['ACCEPT-FLASH'] == true)
		{
			$this->ObTpl->set_block("TPL_ADD_EDIT_BLK","TPL_ACCEPT_FLASH_BLK","bannerAcceptFlash");
			$this->ObTpl->set_block("TPL_ADD_EDIT_BLK","TPL_NO_ACCEPT_FLASH_BLK","blank");
		} else {
			$this->ObTpl->set_block("TPL_ADD_EDIT_BLK","TPL_ACCEPT_FLASH_BLK","blank");
			$this->ObTpl->set_block("TPL_ADD_EDIT_BLK","TPL_NO_ACCEPT_FLASH_BLK","bannerNoAcceptFlash");
		}
		$this->ObTpl->parse("bannerAcceptFlash", "TPL_ACCEPT_FLASH_BLK", true);
		$this->ObTpl->parse("bannerNoAcceptFlash", "TPL_NO_ACCEPT_FLASH_BLK", true);
		
		// Run through the available placeholders
		for ($n=0; $n < count($bannerConfig['BANNER-PLACEHOLDERS']); $n++)
		{
			// Set the form fields for this select box
			$defBP = (isset($_POST['bannerPos']) && ($_POST['bannerPos']==$bannerConfig['BANNER-PLACEHOLDERS'][$n])) ? 'selected="selected"' : '';
			$this->ObTpl->set_var("POST_BANNER_POS", $defBP); 
		
			$this->ObTpl->set_var("PLACEHOLDER_NAME", $bannerConfig['BANNER-PLACEHOLDERS'][$n]);
			$this->ObTpl->parse("bannerPlaces", "TPL_PLACEHOLDERS_BLK", true);
		}
		
		// Set the form fields to defaults or posted values
		$defCN = (isset($_POST['campaignName'])) ? $_POST['campaignName'] : '';
		$this->ObTpl->set_var("POST_CAMPAIGN_NAME", $defCN);
		$defLU = (isset($_POST['linkURL'])) ? $_POST['linkURL'] : '';
		$this->ObTpl->set_var("POST_LINK_URL", $defLU);
		$defBTI = (isset($_POST['bannerType']) && ($_POST['bannerType']=='image')) ? 'selected="selected"' : '';
		$this->ObTpl->set_var("POST_BANNER_TYPE_IMAGE", $defBTI);
		$defBTF = (isset($_POST['bannerType']) && ($_POST['bannerType']=='flash')) ? 'selected="selected"' : '';
		$this->ObTpl->set_var("POST_BANNER_TYPE_FLASH", $defBTF);
		$defBW = (isset($_POST['bannerWidth'])) ? $_POST['bannerWidth'] : '';
		$this->ObTpl->set_var("POST_BANNER_WIDTH", $defBW);
		$defBH = (isset($_POST['bannerHeight'])) ? $_POST['bannerHeight'] : '';
		$this->ObTpl->set_var("POST_BANNER_HEIGHT", $defBH);
		$defDP = (isset($_POST['displayPage'])) ? $_POST['displayPage'] : '';
		$this->ObTpl->set_var("POST_DISPLAY_PAGE", $defDP);
		$defBA = (isset($_POST['bannerActive']) && $_POST['bannerActive']==true) ? 'checked="checked"' : '';
		$this->ObTpl->set_var("POST_BANNER_ACTIVE", $defBA);
		
		// Parse the main table
		$this->ObTpl->parse("addEditTable", "TPL_ADD_EDIT_BLK", true);

		// Define the standard settings	
		$this->ObTpl->set_var("TPL_VAR_SITEURL",SITE_URL);
		$this->ObTpl->set_var("TPL_VAR_SITENAME",SITE_NAME);
		$this->ObTpl->set_var("GRAPHICSMAINPATH",SITE_URL."graphics/");
		
		return($this->ObTpl->parse("return","bannerManager"));
	
	}
	
	#FUNCTION TO DELETE A BANNER
	function deleteBanner()
	{
	
		global $bannerConfig;
		
		// Get the banner ID
		if (!isset($_GET['bannerID'])) return $this->returnError('A banner ID has not been specified');
		
		// Delete the banner from the image file
		$result = $this->obDb->execQry("SELECT vFile FROM ".BANNERS ." WHERE iBannerid_PK='".$_GET['bannerID']."' LIMIT 0,1");
		$num_rows = mysql_num_rows($result);
		if($num_rows > 0)
		{
			$row = mysql_fetch_array($result);
			// If the file exists then delete it
			if (file_exists($_SERVER['DOCUMENT_ROOT'].$bannerConfig['IMAGE-DIR'].$row['vFile']))
			{
				@unlink($_SERVER['DOCUMENT_ROOT'].$bannerConfig['IMAGE-DIR'].$row['vFile']);
			}
			
			// Delete from the database
			$this->obDb->query = "DELETE FROM ".BANNERS." WHERE iBannerid_PK =".$_GET['bannerID'];
			$this->obDb->updateQuery();
			
			// Forward to the summary page
			header("Location: /plugins/bannerManager/");
		
		}
		
		return false;
	
	}
	
	// FUNCTION TO RETURN ANY ERROR STRINGS
	function returnError($errorStr = '')
	{
		$returnStr = "<table style='width:550px;'><tr class='white-a'><th class='prod-builder headline'><a href='javascript:history.go(-1)'><img src='".SITE_URL."graphics/admin/blue/go_back.gif' alt='Go back' /></a> Banner Manager</th></tr><tr class='gray'><td>Error!</td></tr><tr><td style='text-align:left'>";
		$returnStr .= $errorStr;
		$returnStr .= "</td></tr></table>";
		return $returnStr;
	}
	
	// FUNCTION TO RETURN ANY INSTALLATION ERRORS
	function installError($errorType)
	{
		// Define the variable
		$returnStr = "<table style='width:550px;'><tr class='white-a'><th class='prod-builder headline'>Banner Manager</th></tr><tr class='gray'><td>Installation Error!</td></tr><tr><td style='text-align:left'>";
		
		switch ($errorType)
		{
			case "no_dir":
				$returnStr .= "<span style='color:red'>Error: You need to create a folder called 'banners' in your image directory with permissions 0777 (i.e. /images/banners/)</span><br /><br />When you have done this, refresh the page.";
				break;
				
			case "not_writeable":
				$returnStr .= "<span style='color:red'>Error: Cannot write to the 'banners' directory.</span><br /><br />Please set permissions to 0777 (i.e. /images/banners/). When you have done this, refresh the page.";
			break;	
			
			case "no_db_table":
				$returnStr .= "<span style='color:red'>Error: The table '".BANNERS."' does not exist in the database.</span><br /><br /><form action='?addDb=true' method='post'><input type='submit' name='submit' value='Create table and proceed...' /></form><br />Please either use the included .sql file to create the table or click on the link above to create this table. When you have done this, refresh the page.";
			break;
			
			case "create_table":
				$returnStr .= "<span style='color:red'>Error: There was a problem creating the table '".BANNERS."'.</span><br /><br />You will need to use the included .sql file to create it manually. When you have done this, refresh the page.";
			break;
			
			case "no_placeholders":
				$returnStr .= "<span style='color:red'>Error: You need to set placeholders</span><br /><br />You need to add at least 1 placeholder in bannerConfig.php. When you have done this, refresh the page.";
			break;	
				
			default:
				$returnStr .= "No error number given";
		}
		
		$returnStr .= "</td></tr></table>";
		
		return $returnStr;
	
	}
	
	// FUNCTION TO TRACK THE BANNER WHEN CLICKED
	function trackBanner()
	{
		//$referrer = $_SERVER['HTTP_REFERER'];
		//$userIP = $_SERVER['REMOTE_ADDR'];
		
		// If there is no bannerID then return to the home page
		if (!isset($_GET['bannerID'])) header('Location: http://'.$_SERVER['HTTP_HOST']);
		
		// Add the click to the database and forward
		$this->obDb->query="UPDATE ".BANNERS." SET iNumClicks = iNumClicks+1 WHERE iBannerid_PK=".$_GET['bannerID'];
		$this->obDb->updateQuery();
		header('Location: '.$_GET['linkURL']);
		
		return false;
		
	}
	
	// FUNCTION TO SEE IF DATABASE TABLE EXISTS
	function checkDBTable($table)
	{
		$result = $this->obDb->execQry("SHOW TABLES LIKE '".$table."'");
		return (mysql_num_rows($result) == 1) ? true : false;
	}
	
	// FUNCTION TO CREATE THE DATABASE TABLE
	function createDBTable($table)
	{
		$query = "
			CREATE TABLE `" . $table . "` 
			(
			`iBannerid_PK` INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
			`vCampaignName` VARCHAR(30) NOT NULL, 
			`vFile` VARCHAR(30) NOT NULL,
			`vFileType` VARCHAR(5) NOT NULL,
			`vBannerPos` VARCHAR( 20 ) NOT NULL,
			`iWidth` INT(4) NOT NULL,
			`iHeight` INT(4) NOT NULL,
			`vLinkURL` VARCHAR(200) NOT NULL, 
			`vDisplayPage` VARCHAR(200) NOT NULL, 
			`iIsActive` INT(1) NOT NULL DEFAULT '0', 
			`tmAddedDate` varchar(20) NOT NULL, 
			`iPageDisplays` INT(15) NOT NULL DEFAULT '0', 
			`iNumClicks` INT(15) NOT NULL DEFAULT '0')";
		$result = $this->obDb->execQry($query);
		return ($result) ? true : false;
	}
	
	#FUNCTION TO SHOW THE HELP FILE
	function showHelp()
	{
	
		$this->obMainTemplate->set_file('hMainTemplate',MODULES_PATH."default/templates/admin/helpOuter.htm");
	
		$this->ObTpl = new template();
		$this->ObTpl->debug = $this->debugSetting;
		$this->ObTpl->set_file("bannerManager", 'templates/bannerManagerHelp.htm');
	
		// Define the standard settings		
		$this->ObTpl->set_var("TPL_VAR_SITEURL",SITE_URL);
		$this->ObTpl->set_var("TPL_VAR_SITENAME",SITE_NAME);
		$this->ObTpl->set_var("GRAPHICSMAINPATH",SITE_URL."graphics/");
		
		return($this->ObTpl->parse("return","bannerManager"));
	
	}
	
	#FUNCTION TO SHOW A SWF FILE
	function viewSWF()
	{
	
		global $bannerConfig;
	
		$this->ObTpl = new template();
		$this->ObTpl->debug = $this->debugSetting;
		$this->ObTpl->set_file("bannerManager", 'templates/bannerManager.tpl.htm');
		
		// Get the banner ID
		if (!isset($_GET['bannerID'])) return $this->returnError('A banner ID has not been specified');
		
		// Initialise the template blocks and sub-blocks
		$this->ObTpl->set_block("bannerManager","TPL_OVERVIEW_BLK","blank");
		$this->ObTpl->set_block("bannerManager","TPL_ADD_EDIT_BLK","blank");
		$this->ObTpl->set_var("blank","");
		
		// Get the banner contents
		$result = $this->obDb->execQry("SELECT * FROM ".BANNERS ." WHERE iBannerid_PK ='".$_GET['bannerID']."' LIMIT 0,1");
		$num_rows = mysql_num_rows($result);
		if($num_rows > 0)
		{
			$row = mysql_fetch_array($result);
			$this->ObTpl->set_var("SWF_CAMPAIGN_NAME", $row['vCampaignName']);
			$this->ObTpl->set_var("SWF_WIDTH", $row['iWidth']);
			$this->ObTpl->set_var("SWF_HEIGHT", $row['iHeight']);
			$this->ObTpl->set_var("SWF_FILE", $bannerConfig['IMAGE-DIR'].$row['vFile']);
			$this->ObTpl->set_var("SWF_LINK", $row['vLinkURL']);
			$this->ObTpl->set_var("SWF_BANNER_ID", $row['iBannerid_PK']);
		}
	
		// Define the standard settings		
		$this->ObTpl->set_var("TPL_VAR_SITEURL",SITE_URL);
		$this->ObTpl->set_var("TPL_VAR_SITENAME",SITE_NAME);
		$this->ObTpl->set_var("GRAPHICSMAINPATH",SITE_URL."graphics/");
		
		return($this->ObTpl->parse("return","bannerManager"));
	
	}


}
?>
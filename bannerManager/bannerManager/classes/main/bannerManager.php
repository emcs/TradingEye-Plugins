<?php
/*

	Banner Manager Trading Eye plug-in 
	v1.0
	
	Developed by Jim Hill @ Wiseguy Digital
	http://www.wiseguydigital.com
	06 February 2008

*/

// Include the config
include_once(SITE_PATH.'/plugins/bannerManager/bannerConfig.php');

class c_bannerManager {
	 
	function c_bannerManager($obDatabase,$obTemplate)
	{	
		$this->obDb=$obDatabase;
		$this->obTpl=&$obTemplate;
		$this->libFunc=new c_libFunctions();
		$this->bannerConfig = $bannerConfig;
	}#END CONSTRUCTOR

	// A FUNCTION TO CHOOSE THE BANNER FROM THE DATABASE
	function selectBanners()
	{
		global $bannerConfig;
		
		$fullURI = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	
		// Add the banners
		if (is_array($bannerConfig['BANNER-PLACEHOLDERS']) &&count($bannerConfig['BANNER-PLACEHOLDERS']) > 0)
		{
			foreach($bannerConfig['BANNER-PLACEHOLDERS'] as $bannerPos)
			{			
				// Query the database for all relevant banners for this placeholder
				// and that are specifically for this page
				$this->obDb->query = "SELECT * FROM ".BANNERS." WHERE vBannerPos='".$bannerPos."' AND vDisplayPage='".$fullURI."' AND iIsActive=1";
				$result = $this->obDb->execQry($this->obDb->query);
				if (mysql_num_rows($result) > 0)
				{
					
					// Add the results to an array
					$banners = array();
					while ($row = mysql_fetch_array($result)) $banners[] = $row;
					
					// Shuffle the array
					$rand = 0;
					if (count($banners) > 1 ) $rand = array_rand($banners, 1);
					
					$this->__displayBanner($banners[$rand]);
					
					// Add the page display to the database
					$this->obDb->query="UPDATE ".BANNERS." SET iPageDisplays='".($banners[$rand]['iPageDisplays']+1)."' WHERE iBannerid_PK=".$banners[$rand]['iBannerid_PK'];
					$this->obDb->updateQuery();
				
				} else 
				{
				
					// Get all other generic banners for this placeholder
					$this->obDb->query = "SELECT * FROM ".BANNERS." WHERE vBannerPos='".$bannerPos."' AND vDisplayPage='' AND iIsActive=1";
					$result = $this->obDb->execQry($this->obDb->query);
					if (mysql_num_rows($result) > 0)
					{
					
						// Add the results to an array
						$banners = array();
						while ($row = mysql_fetch_array($result)) $banners[] = $row;
						
						// Shuffle the array
						$rand = 0;
						if (count($banners) > 1 ) $rand = array_rand($banners, 1);
					
						$this->__displayBanner($banners[$rand]);
						
						// Add the page display to the database
						$this->obDb->query="UPDATE ".BANNERS." SET iPageDisplays='".($banners[$rand]['iPageDisplays']+1)."' WHERE iBannerid_PK=".$banners[$rand]['iBannerid_PK'];
						$this->obDb->updateQuery();
						
					}
				
				}
				
			}
			
		}
		
	}
	
	// A FUNCTION TO DISPLAY THE ACTUAL BANNER
	function __displayBanner($banner = array())
	{
	
		global $bannerConfig;
	
		// Image file type
		if ($banner['vFileType'] == 'image')
		{
			$toDisplay = '<a href="/plugins/bannerManager/tracker.php?bannerID='.$banner['iBannerid_PK'].'&amp;linkURL='.urlencode($banner['vLinkURL']).'">';
			$toDisplay .= '<img src="'.$bannerConfig['IMAGE-DIR'].$banner['vFile'].'" width="'.$banner['iWidth'].'" height="'.$banner['iHeight'].'" alt="'.$banner['iCampaignName'].'" />';
			$toDisplay .= '</a>';
			
			$this->obTpl->set_var($banner['vBannerPos'], $toDisplay);
			return;
		}
		
		// Flash file type
		if ($banner['vFileType'] == 'flash')
		{
		
			// If flash is not allowed
			if ($bannerConfig['ACCEPT-FLASH'] != true)
			{
				$this->obTpl->set_var($banner['vBannerPos'],"You need to enable Flash in the Banner Manager to view this banner.");
				return;
			}
			
			// Display the Flash Banner using SWF Object
            $toDisplay .= '<div id="'.$banner['vBannerPos'].'">You must install Adobe Flash</div>';
			$toDisplay .= '<script type="text/javascript">';
			$toDisplay .= 'var so = new SWFObject("/plugins/bannerManager/flash/bannerHolder.swf", "banner_'.$banner['vBannerPos'].'", "'.$banner['iWidth'].'", "'.$banner['iHeight'].'", "7", "#ffffff");';
			$toDisplay .= 'so.addVariable("bannerSWF", "'.$bannerConfig['IMAGE-DIR'].$banner['vFile'].'");';
			$toDisplay .= 'so.addVariable("linkURL", "'.urlencode($banner['vLinkURL']).'");';
			$toDisplay .= 'so.addVariable("bannerID", "'.$banner['iBannerid_PK'].'");';
			$toDisplay .= 'so.addVariable("bannerWidth", "'.$banner['iWidth'].'");';
			$toDisplay .= 'so.addVariable("bannerHeight", "'.$banner['iHeight'].'");';
			$toDisplay .= 'so.addParam("scale", "noscale");';
			$toDisplay .= 'so.write("'.$banner['vBannerPos'].'");';
			$toDisplay .= '</script>';
			$this->obTpl->set_var($banner['vBannerPos'], $toDisplay);
		
		}
		
	}
	
}
?>
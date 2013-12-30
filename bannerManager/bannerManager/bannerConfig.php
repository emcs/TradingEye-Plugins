<?php
/*

	Banner Manager Trading Eye plug-in 
	v1.0
	
	Developed by Jim Hill @ Wiseguy Digital
	http://www.wiseguydigital.com
	06 February 2008

*/

$bannerConfig = array();
$bannerConfig['BANNER-PLACEHOLDERS'] = array();

// EDIT BELOW:

// Banners table in database
define("BANNERS",$Prefix.'tbBannerManager');

// Image folder
// This should really stay the same
$bannerConfig['IMAGE-DIR'] = "/images/banners/";

// Accept flash banners?
/* 
This will require installation of SWFObject (http://blog.deconcept.com/swfobject/)
SWF Object needs to be set up in the main layout file to work e.g.
<script type="text/javascript" src="/jscript/swfobject.js"></script> 
and you will need to upload swfobject into the /jscript directory
*/
$bannerConfig['ACCEPT-FLASH'] = true;

// Mark out banner placeholders (Do not use spaces...)
// Simply add the banner placeholders to the array
// E.g.
// $bannerConfig['BANNER-PLACEHOLDERS'][0] = "468x60";
// $bannerConfig['BANNER-PLACEHOLDERS'][1] = "160x600";

$bannerConfig['BANNER-PLACEHOLDERS'][0] = "468x60";
$bannerConfig['BANNER-PLACEHOLDERS'][1] = "160x600";
$bannerConfig['BANNER-PLACEHOLDERS'][2] = "300x250";

?>
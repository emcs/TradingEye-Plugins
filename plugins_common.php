<?php
/*
=======================================================================================
Copyright: Electronic and Mobile Commerce Software Ltd
Product: TradingEye
Version: 7.0.5
=======================================================================================
*/
defined('_TEEXEC') or die;
error_reporting(E_ALL);	
session_start();
	include_once("../../configuration.php");
	include_once(MODULES_PATH."default/authentication.php");
	include_once(MODULES_PATH."default/authorization.php");
	include_once(MODULES_PATH."default/adminleftmenu.php");

class c_commonPlugin{
	function __construct()
	 {
		  $this->c_commonPlugin();
	 }#PHP5 CONSTRUCTOR

	function c_commonPlugin()
	{
		if(!isset($this->attributes) || !is_array($this->attributes)) 
		{
			$this->attributes = array();
			$this->attributes = array_merge($_GET, $_POST, $_FILES); 
		}
		$ar=strstr($_SERVER['PHP_SELF'],"plugins/");
		$array=explode("/",$ar);
		$this->pluginName=$array[1];

		$this->noHelp='../nohelp.htm';
		$this->checkStatus="../inactiveplugin.htm";
		$this->obDb = new database();
		$this->obDb->db_host = DATABASE_HOST;
		$this->obDb->db_user = DATABASE_USERNAME;
		$this->obDb->db_password = DATABASE_PASSWORD;
		$this->obDb->db_port = DATABASE_PORT;
		$this->obDb->db_name = DATABASE_NAME;
		$this->libFunc			=new c_libFunctions();
	}#END CONSTRUCTOR

	function m_displayHelpFile(){
		$this->obDb->query= "SELECT iState,tHelp FROM ".PLUGINS." WHERE vTemplate='".$this->pluginName."'";
		$rsHelp = $this->obDb->fetchQuery();
		if(!$rsHelp[0]->iState){

		}
		if(file_exists($rsHelp[0]->tHelp) && is_file($rsHelp[0]->tHelp)){
			$helpFile=$rsHelp[0]->tHelp;
		}else{
			$helpFile=$this->noHelp;
		}
		$this->obMainTemplate = new Template();
		$this->obMainTemplate->set_file('helpTemplate',$helpFile);
		$this->obMainTemplate->set_var("TPL_VAR_SITEURL",SITE_URL);
		$this->obMainTemplate->set_var("TPL_VAR_SITENAME",SITE_NAME);
		$this->obMainTemplate->set_var("GRAPHICSMAINPATH",SITE_URL."graphics/");
		$this->obMainTemplate->set_var('TPL_VAR_REAL_PATH',str_replace('/plugins/sagecsv','',$this->libFunc->real_path()));
		$this->obMainTemplate->pparse('output', 'helpTemplate');
		exit;
	}

	function m_checkStatus(){
		$this->obDb->query= "SELECT iState FROM ".PLUGINS." WHERE vTemplate='".$this->pluginName."'";
		$rsHelp = $this->obDb->fetchQuery();

		if(!$rsHelp[0]->iState){
	//		$this->obMainTemplate = new Template();
			$this->obMainTemplate->set_file('checkTemplate',$this->checkStatus);
			$this->obMainTemplate->set_var("TPL_VAR_SITEURL",SITE_URL);
			$this->obMainTemplate->set_var("TPL_VAR_SITENAME",SITE_NAME);
			$this->obMainTemplate->set_var("GRAPHICSMAINPATH",SITE_URL."graphics/");
			$this->obMainTemplate->set_var('TPL_VAR_REAL_PATH',str_replace('/plugins/sagecsv','',$this->libFunc->real_path()));
			$this->obMainTemplate->set_var("TPL_VAR_BODY",$this->obMainTemplate->parse('output', 'checkTemplate'));
			return false;
		}
		return true;
	}

	function m_setTempate(){
		$this->obMainTemplate = new Template();
		$this->obMainTemplate->set_file('hMainTemplate',MODULES_PATH."default/templates/admin/default.htm");
		$this->obMainTemplate->set_var("TPL_VAR_SITEURL",SITE_URL);
		$this->obMainTemplate->set_var("TPL_VAR_SITENAME",SITE_NAME);
		$this->obMainTemplate->set_var("GRAPHICSMAINPATH",SITE_URL."graphics/");
		$this->obMainTemplate->set_var('TPL_VAR_REAL_PATH',str_replace('/plugins/sagecsv','',$this->libFunc->real_path()));
		if(!isset($_SESSION['uname']) || empty($_SESSION['uname']))
		{
			$obUserAdmin=new c_authentication($this->obDb,$this->obMainTemplate,$this->attributes);
		}
		$obUserAdmin=new c_leftMenu($this->obDb,$this->obMainTemplate,$this->attributes);
		$obUserAdmin=new c_authorization($this->obDb,$this->obMainTemplate,$this->attributes);
	}
	
	function m_parseTemplate(){
		$this->obMainTemplate->set_var("TPL_VAR_METATITLE",SITE_NAME);	
		$this->obMainTemplate->set_var("TPL_VAR_YEAR",date("Y"));
		$this->obMainTemplate->pparse('output', 'hMainTemplate');
	}
}#EC
	#CHECK HER FOR VALID PLUGIN
?>
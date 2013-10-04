<?php
/*
=======================================================================================
Copyright: Electronic and Mobile Commerce Software Ltd
Product: TradingEye
Version: 7.0.5
=======================================================================================
*/

class c_mobicartSettings {

	function c_mobicartSettings(){
		include('mobiCartFunctions.php');
	}

    function m_mobiCart(){
        $action=explode(".",$this->request['action']);
        $this->ObTpl=new template();
        $this->ObTpl->set_file("TPL_MOBICART_FILE", $this->mobicartTemplate);
        $this->ObTpl->set_block("TPL_MOBICART_FILE", "TPL_MOBICONF_BLOCK", "mobiconf_block");
        $this->ObTpl->set_block("TPL_MOBICONF_BLOCK", "TPL_DEPTIDS_BLK", "deptids_blk");
        //$this->ObTpl->set_block("TPL_MOBICONF_BLOCK", "TPL_PRODIDS_BLK", "prodids_blk");
        $this->ObTpl->set_block("TPL_MOBICART_FILE", "TPL_MOBIERR_BLOCK", "mobierr_block");
        $this->ObTpl->set_var("TPL_VAR_SITEURL",SITE_URL);
        $this->ObTpl->set_var("TPL_VAR_DEPTNAMES","");
        $this->ObTpl->set_var("TPL_VAR_DEPTIDS","");
        /*if(MOBICARTEN == "ERROR_DISABLED") {
            $this->ObTpl->set_var("mobiconf_block", "");
            if (MOBICARTERR == "no license" ){
                $this->ObTpl->set_var("TPL_VAR_MOBIERR", "A license file cannot be found for the MobiCart Intergration plugin, please contact TradingEye for assistance. Until a license file is located the plugin is disabled.");
            } else {
                $this->ObTpl->set_var("TPL_VAR_MOBIERR", MOBICARTERR);
            }
            $this->ObTpl->parse("mobierr_block", "TPL_MOBIERR_BLOCK");
        } else {*/
            $this->ObTpl->set_var("mobierr_block", "");
            if ( MOBICARTEN == "enabled")
			{
                $this->ObTpl->set_var("TPL_VAR_MOBISTATUSEN", "checked");
                $this->ObTpl->set_var("TPL_VAR_MOBISTATUSDI", "");
            }
			else
			{
                $this->ObTpl->set_var("TPL_VAR_MOBISTATUSEN", "");
                $this->ObTpl->set_var("TPL_VAR_MOBISTATUSDI", "checked");
			}
            $this->ObTpl->set_var("TPL_VAR_MOBICARTUSR", (MOBICARTUSR == "NULL" ? "" : MOBICARTUSR));
            $this->ObTpl->set_var("TPL_VAR_MOBICARTAPI", (MOBICARTAPI == "NULL" ? "" : MOBICARTAPI));
            $this->ObTpl->set_var("TPL_VAR_MOBICARTSID", (MOBICARTSID == "NULL" ? "" : MOBICARTSID));
            
            switch($action[1]) {
                case "update":
                    if ( $this->request['MobiCartStatus'] == "enabled") {
                        $mobiCheck = mobi_test_connect($this->request['MobiCartAPI'], $this->request['MobiCartUsr']);
                        if (!$mobiCheck) {
                            $this->ObTpl->set_var("TPL_VAR_MSG", "<div class=\"popNegative\"><p>The information you provided does not authenticate properly or an other error has occured.</p></div>");
                            $this->ObTpl->set_var("TPL_VAR_MOBICARTUSR", $this->request['MobiCartUsr']);
                            $this->ObTpl->set_var("TPL_VAR_MOBICARTAPI", $this->request['MobiCartAPI']);
                            $this->ObTpl->set_var("TPL_VAR_MOBICARTSID", ($this->request['MobiCartSID'] == "NULL" ? "" : $this->request['MobiCartSID']));
                        } else {
                            $this->obDb->query="UPDATE ".SITESETTINGS." SET `vSmalltext` = '{$this->request['MobiCartUsr']}' WHERE `vDatatype` = 'MobiCartUser'";
                            $this->obDb->updateQuery();
                            $this->obDb->query="UPDATE ".SITESETTINGS." SET `vSmalltext` = '{$this->request['MobiCartAPI']}' WHERE `vDatatype` = 'MobiCartAPI'";
                            $this->obDb->updateQuery();
                            if (isset($this->request['MobiCartSID']) && $this->request['MobiCartSID'] != ""){
                                $this->obDb->query="UPDATE ".SITESETTINGS." SET `vSmalltext` = '{$this->request['MobiCartStatus']}' WHERE `vDatatype` = 'MobiCartEnabled'";
                                $this->obDb->updateQuery();
                                $this->obDb->query="UPDATE ".SITESETTINGS." SET `vSmalltext` = '{$this->request['MobiCartSID']}' WHERE `vDatatype` = 'MobiCartStore'";
                                $this->obDb->updateQuery();
                                $this->ObTpl->set_var("TPL_VAR_MSG", "<div class=\"popPositive\"><p>The information you provided has been validated and saved.</p></div>");
                                $this->ObTpl->set_var("TPL_VAR_MOBISTATUSEN", "checked");
                                $this->ObTpl->set_var("TPL_VAR_MOBISTATUSDI", "");
                            } else {
                                $this->ObTpl->set_var("TPL_VAR_MSG", "<div class=\"popPositive\"><p>The information you provided has been validated and saved.</p></div><div class=\"popNegative\"><p>Until the Store ID is entered, MobiCart cannot be enabled..</p></div>");
                                $this->ObTpl->set_var("TPL_VAR_MOBISTATUSEN", "");
                                $this->ObTpl->set_var("TPL_VAR_MOBISTATUSDI", "checked");
                                $this->obDb->query="UPDATE ".SITESETTINGS." SET `vSmalltext` = 'DISABLED' WHERE `vDatatype` = 'MobiCartEnabled'";
                                $this->obDb->updateQuery();
                            }
                            $this->ObTpl->set_var("TPL_VAR_MOBICARTUSR", $this->request['MobiCartUsr']);
                            $this->ObTpl->set_var("TPL_VAR_MOBICARTAPI", $this->request['MobiCartAPI']);
                            $this->ObTpl->set_var("TPL_VAR_MOBICARTSID", ($this->request['MobiCartSID'] == "NULL" ? "" : $this->request['MobiCartSID']));
                        }
                    } else {
                        $this->obDb->query="UPDATE ".SITESETTINGS." SET `vSmalltext` = '{$this->request['MobiCartUsr']}' WHERE `vDatatype` = 'MobiCartUser'";
                        $this->obDb->updateQuery();
                        $this->obDb->query="UPDATE ".SITESETTINGS." SET `vSmalltext` = '{$this->request['MobiCartAPI']}' WHERE `vDatatype` = 'MobiCartAPI'";
                        $this->obDb->updateQuery();
                        $this->obDb->query="UPDATE ".SITESETTINGS." SET `vSmalltext` = '{$this->request['MobiCartStatus']}' WHERE `vDatatype` = 'MobiCartEnabled'";
                        $this->obDb->updateQuery();
                        $this->ObTpl->set_var("TPL_VAR_MOBISTATUSEN", "");
                        $this->ObTpl->set_var("TPL_VAR_MOBISTATUSDI", "checked");
                        $this->ObTpl->set_var("TPL_VAR_MSG", "<div class=\"popPositive\"><p>Your settings have been saved.</p></div>");
                    }
                break;
                case "sync":
					$this->send2Mobi();
				break;
                default:
                    $this->ObTpl->set_var("TPL_VAR_MSG", "");
                break;
            }
			
			//Mobicart Sync User Interface
			
			//Get Dept Ids and Titles
            $this->obDb->query="SELECT d.iDeptid_PK as dept_id,d.vTitle as dept_title FROM ".DEPARTMENTS." as d INNER JOIN ".FUSIONS." as f ON f.iSubId_FK=d.iDeptid_PK WHERE iOwner_FK=\"0\" AND vtype=\"department\" AND iState=\"1\"";
            $result = $this->obDb->fetchQuery();
				//I dont think you can assign products to homepage
				//$this->ObTpl->set_var("TPL_VAR_DEPTIDS",0);
				//$this->ObTpl->set_var("TPL_VAR_DEPTNAMES","Homepage");
				//$this->ObTpl->parse("deptids_blk","TPL_DEPTIDS_BLK",true);
				$templist = " AND 1=2";
				$tempowners = "-1,0";
			foreach($result as $key => $value)
			{
				$templist = $templist . " OR f.iOwner_FK = " . $result[$key]->dept_id . " AND vOwnerType=\"department\" AND vtype=\"department\" AND iState=\"1\"";
				$this->ObTpl->set_var("TPL_VAR_DEPTIDS",$result[$key]->dept_id);
				$this->ObTpl->set_var("TPL_VAR_DEPTNAMES",stripslashes($result[$key]->dept_title));
				$tempowners = $tempowners . "|" . "0" . "," . $result[$key]->dept_id;
				$this->ObTpl->parse("deptids_blk","TPL_DEPTIDS_BLK",true);
			}
			$this->obDb->query="SELECT d.iDeptid_PK as dept_id,d.vTitle as dept_title,f.iOwner_FK as dept_owner FROM ".DEPARTMENTS." as d INNER JOIN ".FUSIONS." as f ON f.iSubId_FK=d.iDeptid_PK WHERE vOwnerType=\"department\" AND vtype=\"department\" AND iState=\"1\"";
			$this->obDb->query = $this->obDb->query . $templist;
            $result = $this->obDb->fetchQuery();
			if($this->obDb->record_count > 0)
			{
			foreach($result as $key => $value)
			{
				$this->ObTpl->set_var("TPL_VAR_DEPTIDS",$result[$key]->dept_id);
				$this->ObTpl->set_var("TPL_VAR_DEPTNAMES",stripslashes($result[$key]->dept_title));
				$tempowners = $tempowners . "|" . $result[$key]->dept_owner . "," . $result[$key]->dept_id;
				$this->ObTpl->parse("deptids_blk","TPL_DEPTIDS_BLK",true);
			}
			}
			$this->ObTpl->set_var("TPL_VAR_DEPTOWNERS",$tempowners);
			//Get All Depts, Products, and their fusions
            $this->obDb->query="SELECT f.iOwner_FK as dept_id,d.vTitle as dept_title,GROUP_CONCAT(CAST(CONCAT(p.iProdid_PK,\",\",p.vTitle) as CHAR)) as prod_ids FROM ".FUSIONS." as f LEFT JOIN ".DEPARTMENTS." AS d ON f.iOwner_FK=d.iDeptid_PK INNER JOIN ".PRODUCTS." AS p on f.iSubId_FK=p.iProdid_PK WHERE vtype=\"product\" AND vOwnertype=\"department\" GROUP BY dept_id";
            $result = $this->obDb->fetchQuery();
			$tempstring = "";
			$tempstring2 = "";
			/*foreach($result as $key => $value)
			{
				// allprod[deptid] = [name,[prodid,name],[prodid,name],[prodid,name]]; for each dept
				$tempstring = $tempstring . "allprod[".$result[$key]->dept_id."] = Array(\"".$result[$key]->dept_title."\",".$result[$key]->prod_ids . ");\n									";
			}*/
			foreach($result as $key => $value)
			{
				$x = 0;
				$temparray[$result[$key]->dept_id] = Array($result[$key]->dept_title,explode(",",$result[$key]->prod_ids));
				$temparray2[$x] = Array($result[$key]->dept_id,$result[$key]->dept_title,explode(",",$result[$key]->prod_ids));
				$x = $x + 1;
				
			}
			$tempstring = json_encode($temparray);
			$tempstring2 = json_encode($temparray2);
			$this->ObTpl->set_var("TPL_VAR_DEPTPRODJSARR",$tempstring);
			$this->ObTpl->set_var("TPL_VAR_DEPTPRODJSARR2",$tempstring2);
			$this->ObTpl->set_var("TPL_VAR_AJAX",SITE_URL."admin/adminindex.php?action=mobicart.sync");
            $this->ObTpl->parse("mobiconf_block", "TPL_MOBICONF_BLOCK");
        //}
        return($this->ObTpl->parse("return", "TPL_MOBICART_FILE"));
    }
	function send2Mobi()
	{
		//select from database each id provided
		//take each result and check if it exists on mobicart, if so, update it, if not, add it.

		//owners=-1%2C0%7C0%2C1%7C0%2C2%7C0%2C9%7C9%2C10&prod0=0%2C2&prod1=0%2C4&prod2=0%2C1&count=3

		//PREPARE IDS, NEED COUNT
		//print_r($_POST);
		$count = $_POST['count'];
		$x = 0;
		$toadd = Array();
		//echo "owners:".$_POST['owners'];
		$temparray= explode("|",$_POST['owners']);
		//print_r($temparray);
		$deptsqlstring;
		$prodstring;
		$owners = Array();
		FOREACH($temparray as $key => $value)
		{
			$temparray2 = explode(",",$value);
			//owners array matches a department primary key to its owner's primary key
			$owners[$temparray2[1]] = $temparray2[0];
		}
		//print_r($temparray2);
		//echo "<br/>\n";
		//print_r($owners);
		$temparray = Array();
		WHILE($x < $count)
		{
			//echo "prod" . $x;
			$temparray = explode(",",$_POST['prod' . $x]);
			//print_r($temparray);
			//converts a TE product id into a owning deptid
			$toadd[0][$temparray[1]] = $temparray[0];
			//converts TE department id into array of product ids to be added
			$toadd[1][$temparray[0]][] = $temparray[1];
			if($x == 0)
			{
				$deptsqlstring = " WHERE iDeptid_PK=" . $temparray[0];
				$prodstring = " WHERE p.iProdid_PK=" . $temparray[1];
				//used for options
				$prodstring2 = " WHERE iProductid_FK=" . $temparray[1];
			}
			else
			{
				$deptsqlstring = $deptsqlstring . " OR iDeptid_PK=" . $temparray[0];
				$prodstring = $prodstring . " OR p.iProdid_PK=" . $temparray[1];
				$prodstring2 = $prodstring . " OR iProductid_FK=" . $temparray[1];
			}
			$x = $x  + 1;
		}

		//Get All MobiDepartments
		//https://www.mobi-cart.com/api/store-departments.json?api_key=4c7f1cf1b696d1ac82f7607ba565c3f0&user_name=jordan@tradingeye.com&store_id=8573
		//get json result
		$tempjson = mobi_get_json("https://www.mobi-cart.com/api/store-departments.json?api_key=".MOBICARTAPI."&user_name=".MOBICARTUSR."&store_id=".MOBICARTSID);
		//turn json into array
		$mobiDepts = json_decode($tempjson,true);
		//print_r($mobiDepts);
		//Get TE Departments
		$this->obDb->query="SELECT iDeptid_PK,vTitle FROM ".DEPARTMENTS . $deptsqlstring;
		$result = $this->obDb->fetchQuery();
		//Compare each dept to mobidepts to find which ones need to be inserted

		//converts a TE Dept ID into a Mobi Dept ID
		$te2mobi = Array();
		//print_r($result);
		//sets up te2mobi
		$errorcount = 0;
		$errorstring = "";
		FOREACH($mobiDepts["DepartmentList"]["departments"] as $key => $value)
		{
			
			$startindex = 1;
			$returned = 20;
			WHILE($returned == 20)
			{
				//echo "looking for products\n";
				$tempjson = mobi_get_json("https://www.mobi-cart.com/api/department-products.json?api_key=".MOBICARTAPI."&user_name=".MOBICARTUSR."&store_id=".MOBICARTSID."&department_id=".$mobiDepts["DepartmentList"]["departments"][$key]["departmentId"]."&start_index=".$startindex);
				$tempjson = json_decode($tempjson,true);
				//print_r($tempjson);
				if(isset($tempjson["error"]))
				{
					$returned = 0;
					//echo "error";
					break;
				}
				if(isset($tempjson["products"]["products"]))
				{
					//echo "products detected\n";
					//decides if the loop is continued.
					$returned = 0;
					FOREACH($tempjson["products"]["products"] as $key2 => $value2)
					{
						//echo "deleting a product\n";
						$tempjson2 = mobi_send_delete("https://www.mobi-cart.com/api/delete-product.json?api_key=".MOBICARTAPI."&user_name=".MOBICARTUSR."&store_id=".MOBICARTSID."&product_id=".$tempjson["products"]["products"][$key2]["productId"]);
						$tempjson2 = json_decode($tempjson2,true);
						//print_r($tempjson2);
						if(isset($tempjson2["error"]))
						{
							//echo "products deleted error\n";
							$errorcount = $errorcount + 1;
							$errorstring = $errorstring . "\n" . ",Error deleting product titled '".$tempjson["products"]["products"][$key2]["productName"]."' from MobiCart,".$tempjson2["error"]["errorcode"]." - ".$tempjson2["error"]["message"];
						}
						$returned = $returned + 1;
					}
				}
				$startindex = $startindex + 20;
			}
			
		}
		//$tempjson = mobi_get_json("https://www.mobi-cart.com/api/store-products.json?api_key=".MOBICARTAPI."&user_name=".MOBICARTUSR."&store_id=".MOBICARTSID);
		//$tempjson = json_decode($tempjson);
		//print_r($mobiDepts["DepartmentList"]["departments"]);
			FOREACH($mobiDepts["DepartmentList"]["departments"] as $key2 => $value2)
			{
								//deletes each department on mobicart
				$tempjson = mobi_send_delete("https://www.mobi-cart.com/api/delete-department.json?api_key=".MOBICARTAPI."&user_name=".MOBICARTUSR."&store_id=".MOBICARTSID."&department_id=".$mobiDepts["DepartmentList"]["departments"][$key2]["departmentId"]."&_method=delete");
				$tempjson = json_decode($tempjson,true);
				//print_r($tempjson);
				if(isset($tempjson["error"]))
				{
					$errorcount = $errorcount + 1;
					$errorstring = $errorstring . "\n" . ",Error deleting department titled '".$mobiDepts["DepartmentList"]["departments"][$key2]["departmentName"]."' from MobiCart,".$tempjson["error"]["errorcode"]." - ".$tempjson["error"]["message"];
				}
			}

		FOREACH($result as $key => $value)
		{
			//makes sure that these domains arent subdomains as the top domains must be created first
			if($owners[$value->iDeptid_PK][0] == 0)
			{
				//$postring = "api_key=".MOBICARTAPI."&user_name=".MOBICARTUSR."&store_id=".MOBICARTSID;
				$tempjson = mobi_post_json_dept($value->vTitle);
				$tempjson = json_decode($tempjson,true);
				if(isset($tempjson["error"]))
				{
					$errorcount = $errorcount + 1;
					$errorstring = $errorstring . "\n" . ",Error sending department titled '".$value->vTitle."' to MobiCart,".$tempjson["error"]["errorcode"]." - ".$tempjson["error"]["message"];
				}
				else
				{
					$te2mobi[$value->iDeptid_PK] = $tempjson["message"]["id"];
				}
			}
		}
		FOREACH($result as $key => $value)
		{
			//makes sure that these domains arent subdomains as the top domains must be created first
			if($owners[$value->iDeptid_PK][0] != 0)
			{
				$postring = "api_key=".MOBICARTAPI."&user_name=".MOBICARTUSR."&store_id=".MOBICARTSID."&department_id=".$te2mobi[$owners[$value->iDeptid_PK][0]]."&category_name=".$value->vTitle."&category_status=true";
				$tempjson = mobi_send_post("http://www.mobi-cart.com/api/add-sub-department-nested.json",$poststring);
				$tempjson = json_decode($tempjson,true);
				if(isset($tempjson["error"]))
				{
					$errorcount = $errorcount + 1;
					$errorstring = $errorstring . "\n" . ",Error sending subdepartment titled '".$value->vTitle."' to MobiCart,".$tempjson["error"]["errorcode"]." - ".$tempjson["error"]["message"];
				}
				else
				{
					$te2mobi[$value->iDeptid_PK] = $tempjson["message"]["id"];
				}
			}
		}

		//SELECT PRODUCTS TO ADD
		//mobi_add_prod($dept, $title, $desc, $status = "active", $price, $image, $sku, $video = "", $inv, $use_inv)
		
		//products
		$this->obDb->query="SELECT p.vTitle as pname, p.iProdid_PK as pid,tContent as pdesc,p.fPrice as pprice,tImages as ptimages,vSku psku,p.iInventory as pstock,p.iUseinventory as pUseInv, o.vName as otitle, ov.vItem as oname,ov.iInventory as ostock,ov.iUseinventory as oUseInv,ov.vOptSku as osku,ov.fPrice as oprice,id.iProductid_FK as oid FROM ".PRODUCTS." as p LEFT JOIN ".PRODUCTOPTIONS." as id on id.iProductid_FK=p.iProdid_PK LEFT JOIN ".OPTIONS." as o on id.iOptionid=o.iOptionid_PK LEFT JOIN ".OPTIONVALUES." as ov on id.iOptionid=ov.iOptionid_FK" . $prodstring;
		$result = $this->obDb->fetchQuery();
		//print_r($result);
		//options
		//$this->obDb->query="SELECT o.vName as title, ov.vItem as name,ov.iInventory as stock,ov.vOptSku as sku,ov.fPrice as price FROM ".PRODUCTOPTIONS . " as id INNER JOIN ".OPTIONS." as o on id.iOptionid=o.iOptionid_PK INNER JOIN ".OPTIONVALUES." as ov on id.iOptionid=ov.iOptionid_FK" . $prodstring;
		//$resulto = $this->obDb->fetchQuery();
		//0 = add product
		//1 = add option
		$oid = 0;
		$lastid = "-1";
		//print_r($result);
		FOREACH($result as $key => $value)
		{
			if($lastid != $result[$key]->pid)
			{
				$mode = 0;
			}
			$usestock = "";
			if($result[$key]->pUseInv == "1")
			{
				$usestock = "products";
			}
			elseif($result[$key]->oUseInv == "1")
			{
				$usestock = "options";
			}
			//is inserting product
			if($mode == 0)
			{
				$tempjson = mobi_add_prod2($te2mobi[$toadd[0][$result[$key]->pid]], $result[$key]->pname, $result[$key]->pdesc, "active", $result[$key]->pprice, "", "", $result[$key]->psku, "", $result[$key]->pstock, $usestock);
				$tempjson = json_decode($tempjson,true);
				if(isset($tempjson["error"]))
				{
					$errorcount = $errorcount + 1;
					$errorstring = $errorstring . "\n" . ",Error sending product titled '".$result[$key]->pname."' to MobiCart,".$tempjson["error"]["errorcode"]." - ".$tempjson["error"]["message"];
				}
				if(isset($tempjson["message"]["id"]))
				{
					$newID = $tempjson["message"]["id"];
					//echo "\nnewid:" . $newID;
				}
				//echo "\nProd:".$result[$key]->pid." - optionpid:".$result[$key]->oid;
				if($result[$key]->pid == $result[$key]->oid)
				{
					//echo "\nMode is now 1";
					$mode = 1;
				}
				//echo "\nImages:".$result[$key]->ptimages." newid:".$newID;
				if(isset($result[$key]->ptimages) && !empty($result[$key]->ptimages) && isset($newID) && !empty($newID))
				{
					//add images to product
					//echo "\nimage being inserted";
					$temparray = Array();
					$temparray = explode(",",$result[$key]->ptimages);
					FOREACH($temparray as $key2 => $value2)
					{
						if(!empty($value2))
						{
							//echo "adding image(".SITE_URL."images/product/".$value2;
							$data ="api_key=".MOBICARTAPI."&user_name=".MOBICARTUSR."&product_id=".$newID."&product_image_url=".SITE_URL."images/product/".$value2;
							$tempjson = mobi_send_post("https://www.mobi-cart.com/api/add-product-image.json",$data);
							$tempjson = json_decode($tempjson,true);
							//print_r($tempjson);
							if(isset($tempjson["error"]))
							{
								$errorcount = $errorcount + 1;
								if($tempjson["error"]["errorcode"] == 1004)
								{
									$errorstring = $errorstring . "\n" . ",Error adding image(".SITE_URL."images/product/".$value2.") to MobiCart,".$tempjson["error"]["errorcode"]." - File Not Found. Reset your images in shop builder";
								}
								else
								{
									$errorstring = $errorstring . "\n" . ",Error adding image(".SITE_URL."images/product/".$value2.") to MobiCart,".$tempjson["error"]["errorcode"]." - ".$tempjson["error"]["message"];
								}
							}
						}
					}
				}
				
			}
			//is inserting a option to a product
			if($mode == 1 && isset($newID))
			{
				//echo "option being inserted";
				if($result[$key]->oUseInv == 1)
				{
					$stockoption = "true";
				}
				else
				{
					$stockoption = "false";
				}
				//do options
				//USER NAME: user_name (Mandatory),API KEY: api_key (Mandatory),PRODUCT ID: product_id (Mandatory),SKU-ID: sku_id,OPTION TITLE: option_title (Mandatory),OPTION NAME: option_name (Mandatory),AVAILABLE QUANTITY: option_quantity,STOCK ON OPTIONS: stock_on_options("true" or "false"),OPTION PRICE: option_price
				$data ="api_key=".MOBICARTAPI."&user_name=".MOBICARTUSR."&product_id=".$newID."&sku_id=".$result[$key]->osku."&option_title=".$result[$key]->otitle."&option_name=".$result[$key]->oname."&option_quantity=".$result[$key]->ostock."&stock_on_options=".$stockoption."&option_price=".$result[$key]->oprice;
				$tempjson = mobi_send_post("https://www.mobi-cart.com/api/add-productOption.json",$data);
				$tempjson = json_decode($tempjson,true);
				//print_r($tempjson);
				if(isset($tempjson["error"]))
				{
					$errorcount = $errorcount + 1;
					$errorstring = $errorstring . "\n" . ",Error adding option(".$value->oname.") to MobiCart,".$tempjson["error"]["errorcode"]." - ".$tempjson["error"]["message"];
				}
				$oid=$oid + 1;
			}
			$lastid = $result[$key]->pid;
		}
		echo "TradingEye > Mobicart Sync Complete.\nErrors:".$errorcount."\n". $errorstring . "|||";
	}
}

?>
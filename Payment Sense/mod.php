"{MODULES_PATH}admin/classes/admin/settings_interface.php","replace","//PAYPAL DIRECT PAYMENTS","		//Payment Sense\n		$this->ObTpl->set_var(\"TPL_VAR_PSMERCHANTID\",PS_MERCHANT_ID);\n		$this->ObTpl->set_var(\"TPL_VAR_PSPASSWORD\",PS_MERCHANT_PASS);\n		$this->ObTpl->set_var(\"TPL_VAR_PSKEY\",PS_SECRET_KEY);\n		$this->ObTpl->set_var(\"TPL_VAR_PSURL\",PS_GATEWAY_DOMAIN);\n		$this->ObTpl->set_var(\"TPL_VAR_PSPORT\",PS_GATEWAY_PORT);\n		$this->ObTpl->set_var(\"TPL_VAR_PSCURRENCY\",PS_CURRENCY);\n		\n		//Payment Sense Hosted\n		$this->ObTpl->set_var(\"PSr_MERCHANT_ID\",PSr_MERCHANT_ID);\n		$this->ObTpl->set_var(\"PSr_MERCHANT_PASS\",PSr_MERCHANT_PASS);\n		$this->ObTpl->set_var(\"PSr_KEY\",PSr_KEY);\n		$this->ObTpl->set_var(\"PSr_DOMAIN\",PSr_DOMAIN);\n		$this->ObTpl->set_var(\"PSr_RESULTS_DISPLAY\",PSr_RESULTS_DISPLAY);\n		$this->ObTpl->set_var(\"PSr_CV2_MANDATORY\",PSr_CV2_MANDATORY);\n		$this->ObTpl->set_var(\"PSr_VAR_CURRENCY\",PSr_CURRENCY);\n		\n		//PAYPAL DIRECT PAYMENTS"
"{MODULES_PATH}admin/admin_controller.php","replace","$obOrd->settingsTemplate=$this->templatePath.\"paymentSetting.tpl.htm\";","$pluginInterface = new pluginInterface();\n$obOrd->settingsTemplate=$pluginInterface->plugincheck($this->templatePath.\"paymentSetting.tpl.htm\");"
"{ADMIN_THEME_PATH}admin/templates/admin/paymentSetting.tpl.htm","replace","<option value=\"propay\">Propay</option>","<option value=\"payment sense\">Payment Sense</option>\n							<option value=\"propay\">Propay</option>"
"{ADMIN_THEME_PATH}admin/templates/admin/paymentSetting.tpl.htm","replace","<!-- (ends) cardsave direct integrated method -->","<!-- (ends) cardsave direct integrated method -->	\n				 <!-- (begin) payment sense direct integrated method -->\n				<div class=\"AccordionPanel\">\n				  <div class=\"AccordionPanelTab colaspTitle2\"><img src=\"../images/collasp-arrow.png\" alt=\"collasp\" /><div>Payment Sense Direct</div></div>\n					<div class=\"AccordionPanelContent colaspWrapper colaspContent2\">\n					<table class='whiteTable2'>\n							<thead>\n								<tr>\n									<th colspan=\"2\">Payment Sense Direct<a href=\"http://www.paymentsense.co.uk/e-commerce/payment-gateway/\" target=\"_blank\">Signup Now</a> (link opens in a new window)</th>\n								</tr>\n							</thead>\n							<tbody>\n								<tr>\n									<td class=\"first\"><label>Merchant ID</label></td>\n									<td><input type=\"text\" class=\"formField\" id=\"PaymentSenseID\" name=\"txtPaymentSenseID\" value=\"{TPL_VAR_PSMERCHANTID}\" /></td>\n								</tr>\n								<tr>\n									<td class=\"first\"><label>Password</label></td>\n									<td><input type=\"text\" class=\"formField\" id=\"PaymentSensePass\" name=\"txtPaymentSensePass\" value=\"{TPL_VAR_PSPASSWORD}\" /></td>\n								</tr>\n								<tr>\n									<td class=\"first\"><label>Gateway Domain</label></td>\n									<td><input type=\"text\" class=\"formField\" id=\"PaymentSenseURL\" name=\"txtPaymentSenseURL\" value=\"{TPL_VAR_PSURL}\" /></td>\n								</tr>\n								<tr>\n									<td class=\"first\"><label>Gateway Port</label></td>\n									<td><input type=\"text\" class=\"formField\" id=\"PaymentSensePORT\" name=\"txtPaymentSensePORT\" value=\"{TPL_VAR_PSPORT}\" /></td>\n								</tr>\n								<tr>\n									<td class=\"first\"><label>Secret Key</label></td>\n									<td><input type=\"text\" class=\"formField\" id=\"PaymentSenseKey\" name=\"txtPaymentSenseKey\" value=\"{TPL_VAR_PSKEY}\" /></td>\n								</tr>\n								<tr>\n									<td class=\"first\"><label>Currency</label></td>\n									<td><input type=\"text\" class=\"formField\" id=\"PaymentSenseCurrency\" name=\"txtPaymentSenseCurrency\" value=\"{TPL_VAR_PSCURRENCY}\" /></td>\n								</tr>\n							</tbody>	\n						</table>\n					</div>\n				</div> \n\n<!-- (ends) payment sense direct integrated method -->"
"{ADMIN_THEME_PATH}admin/templates/admin/paymentSetting.tpl.htm","replace","<!-- (END) cardsave redirect hosted method -->","<!-- (END) cardsave redirect hosted method -->	         \n                 <!-- (BEGIN) payment sense redirect hosted method -->\n				<div class=\"AccordionPanel\">\n							  <div class=\"AccordionPanelTab colaspTitle2\"><img src=\"../images/collasp-arrow.png\" alt=\"collasp\" /><div>Payment Sense Hosted</div></div>\n								<div class=\"AccordionPanelContent colaspWrapper colaspContent2\" >\n								<table class='whiteTable2'>\n										<thead>\n											<tr>\n\n\n												<th colspan=\"2\">Payment Sense Hosted<a href=\"http://www.paymentsense.co.uk/e-commerce/payment-gateway/\" class=\"linkSignup\" target=\"_blank\">Signup Now</a> (link opens in a new window)</th>\n											</tr>\n										</thead>\n										<tbody>\n											<tr>\n\n												<td class=\"first\"><label>Merchant ID</label></td>\n												<td><input type=\"text\" class=\"formField\" id=\"PaymentRSenseMerchantID\" name=\"txtPaymentRSenseMerchantID\" value=\"{PSr_MERCHANT_ID}\" /></td>\n											</tr>\n											<tr>\n												<td class=\"first\"><label>Password</label></td>\n												<td><input type=\"text\" class=\"formField\" id=\"PaymentRSensePassword\" name=\"txtPaymentRSensePassword\" value=\"{PSr_MERCHANT_PASS}\" /></td>\n											</tr>\n											<tr>\n												<td class=\"first\"><label>Pre Shared Key</label></td>\n												<td><input type=\"text\" class=\"formField\" id=\"PaymentRSenseKey\" name=\"txtPaymentRSenseKey\" value=\"{PSr_KEY}\" /></td>\n											</tr>\n											<tr>\n												<td class=\"first\"><label>Gateway Domain</label></td>\n												<td><input type=\"text\" class=\"formField\" id=\"PaymentRSenseDomain\" name=\"txtPaymentRSenseDomain\" value=\"{PSr_DOMAIN}\" /></td>\n											</tr>\n											<tr>\n												<td class=\"first\"><label>Results</label></td>\n\n												<td>\n													<select id=\"PaymentRSenseResults\" name=\"txtPaymentRSenseResults\"/>\n														<option value=\"0\">Only TradingEye Displays Results</option>\n														<option value=\"1\">Hosted Payment Form Displays Results</option>\n													</select>\n													<script type=\"text/javascript\">\n														jQuery(\"#PaymentRSenseResults\").val('{PSr_RESULTS_DISPLAY}');\n													</script>\n												</td>\n											</tr>\n											<tr>\n												<td class=\"first\"><label>CV2</label></td>\n												<td><input type=\"text\" class=\"formField\" id=\"PaymentRSenseCV2\" name=\"txtPaymentRSenseCV2\" value=\"{PSr_CV2_MANDATORY}\" /></td>\n											</tr>\n											<tr>\n												<td class=\"first\"><label>Currency</label></td>\n												<td><input type=\"text\" class=\"formField\" id=\"PaymentRSenseCurrency\" name=\"txtPaymentRSenseCurrency\" value=\"{PSr_VAR_CURRENCY}\" /></td>\n											</tr>\n										</tbody>	\n									</table>\n								</div>\n							</div>  \n<!-- (END) payment sense redirect hosted method -->"
"{MODULES_PATH}ecom/ecom_controller.php","replace","include_once($pluginInterface->plugincheck(MODULES_PATH.\"ecom/classes/main/saveOrder.php\")); ","include_once($pluginInterface->plugincheck(MODULES_PATH.\"ecom/classes/main/saveOrder.php\")); \nrequire_once($pluginInterface->plugincheck(SITE_PATH.\"plugins/Payment Sense/classes/paymentSense.php\"));"
"{MODULES_PATH}ecom/ecom_controller.php","replace","$obreceipt=new c_receipt();\n			$obreceipt->obTpl=$this->obTpl;\n			$obreceipt->obDb=$this->obDb;\n			$obreceipt->request=$this->request;\n			$this->libFunc=new c_libFunctions();","$obreceipt=new c_receipt();\n			$obreceipt->obTpl=$this->obTpl;\n			$obreceipt->obDb=$this->obDb;\n			$obreceipt->request=$this->request;\n			$this->libFunc=new c_libFunctions();\n			\n\n			$paymentSense = new c_paymentSense();\n			$paymentSense->obDb=$this->obDb;\n			$paymentSense->obTpl=$this->obTpl;\n			$paymentSense->request=$this->request;\n			$paymentSense->libFunc=$this->libFunc;"
"{MODULES_PATH}ecom/ecom_controller.php","replace","case \"cshcb2\":\n					$cardSave->m_cardSave_Hosted_Callback(\"1\");\n				break;","case \"cshcb2\":\n					$cardSave->m_cardSave_Hosted_Callback(\"1\");\n				break;\n				case \"ps3d\":\n					$paymentSense->m_PaymentSense_3D1();\n				break;\n				case \"ps3d2\":\n					$paymentSense->m_PaymentSense_3D2();\n				break;\n				case \"ps3dr\":\n					$paymentSense->m_PaymentSense_3DR();\n				break;\n				case \"pshcb\":\n					$paymentSense->m_PaymentSense_Hosted_Callback(\"0\");\n				break;\n				case \"pshcb2\":\n					$paymentSense->m_PaymentSense_Hosted_Callback(\"1\");\n				break;"
"{MODULES_PATH}ecom/ecom_controller.php","replace","$obBill->billShipTemplate=$this->templatePath.\"ConfirmOrderAndBillShip.tpl.htm\"","$pluginInterface = new pluginInterface();\n$obBill->billShipTemplate=$pluginInterface->plugincheck($this->templatePath.\"ConfirmOrderAndBillShip.tpl.htm\")"
"{MODULES_PATH}ecom/classes/main/billShipInfo.php","replace","$this->ObTpl->set_block(\"TPL_USER_FILE\",\"TPL_SAGEPAYFORM_BLK\",\"sagepayform_blk\");\n    	#(END) SAGE PAY INTERGRATION","$this->ObTpl->set_block(\"TPL_USER_FILE\",\"TPL_SAGEPAYFORM_BLK\",\"sagepayform_blk\");\n    	#(END) SAGE PAY INTERGRATION\n        $this->ObTpl->set_block(\"TPL_USER_FILE\",\"TPL_PS_FORM_BLK\",\"psform_blk\");"
"{MODULES_PATH}ecom/classes/main/billShipInfo.php","replace","$this->ObTpl->set_var(\"sagepayform_blk\",\"\");\n		#(END) SAGE PAY INTERGRATION","$this->ObTpl->set_var(\"sagepayform_blk\",\"\");\n		#(END) SAGE PAY INTERGRATION\n		$this->ObTpl->set_var(\"psform_blk\",\"\");"
"{MODULES_PATH}ecom/classes/main/billShipInfo.php","replace","$this->ObTpl->parse(\"sagepayform_blk\",\"TPL_SAGEPAYFORM_BLK\");\n			}\n            #(BEGIN) SAGE PAY INTERGRATION","$this->ObTpl->parse(\"sagepayform_blk\",\"TPL_SAGEPAYFORM_BLK\");\n			}\n            #(BEGIN) SAGE PAY INTERGRATION\n			\n            #BEGIN PAYMENT SENSE INTERGRATION\n             if (PSr_MERCHANT_ID!=\"\" && PSr_MERCHANT_PASS!=\"\" && PSr_KEY!=\"\" && PSr_RESULTS_DISPLAY!=\"\" && PSr_CV2_MANDATORY!=\"\" )\n			{\n				$blkactive=1;\n				$this->ObTpl->parse(\"psform_blk\",\"TPL_PS_FORM_BLK\");\n			}\n            #END PAYMENT SENSE INTERGRATION"
"{MODULES_PATH}ecom/classes/main/saveOrder.php","replace","#(BEGIN) SAGEPAY INTERGRATION \n				case \"sagepayform\":","				case \"payment sense\":\n					$paymentSense = new c_paymentSense();\n					$paymentSense->obDb=$this->obDb;\n					//not sure how this works yet...\n					//$paymentSense->obTpl=new Template();\n					//$paymentSense->obTpl->set_file('hMainTemplate',SITE_PATH.'plugins/Payment Sense/templates/blank.htm');\n					$paymentSense->request=$this->request;\n					$paymentSense->m_PaymentSense_Hosted($_SESSION['order_id']);\n					//return $paymentSense->obTpl->parse(\"return\",\"hTemplate\");\n					exit;\n				break;\n				\n                #(BEGIN) SAGEPAY INTERGRATION \n				case \"sagepayform\":"
"{MODULES_PATH}ecom/classes/main/saveOrder.php","replace","case \"protx\":	\n						$this->m_sagepaySubmit();","					case \"payment sense\":\n						$paymentSense = new c_paymentSense();\n						$paymentSense->obDb=$this->obDb;\n						$paymentSense->obTpl=$this->obTpl;\n						$paymentSense->request=$this->request;\n\n\n						$paymentSense->m_PaymentSense_Direct($_SESSION['order_id']);\n						exit;\n					break;\n					case \"protx\":	\n						$this->m_sagepaySubmit();"
"{THEME_PATH}ecom/templates/main/ConfirmOrderAndBillShip.tpl.htm","replace","<!-- (END) SAGEPAY INTEGRATION -->","<!-- (END) SAGEPAY INTEGRATION -->\n				<!-- (BEGIN) PAYMENT SENSE INTEGRATION -->\n            <div class=\"billShipBlock\">\n				<!-- BEGIN TPL_PS_FORM_BLK -->\n				<h3><input id=\"psform\" type=\"radio\" name=\"paymethod\" class=\"formRadio\" value=\"payment sense\" />Pay by Credit/Debit card using PaymentSense</h3>\n				<p><img class=\"paymentGateway\" src=\"../plugins/Payment Sense/images/ps_logo.png\" alt=\"Payment Sense Logo.\" /><br/>You will be taken to the <strong>PaymentSense</strong> website to make your payment.</p>\n				<!-- END TPL_PS_FORM_BLK -->\n			</div>\n			<!-- (END) PAYMENT SENSE INTEGRATION -->"
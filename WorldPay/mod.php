"{MODULES_PATH}admin/admin_controller.php","replace","$obOrd->settingsTemplate=$this->templatePath.\"paymentSetting.tpl.htm\";","$pluginInterface = new pluginInterface();\n$obOrd->settingsTemplate=$pluginInterface->plugincheck($this->templatePath.\"paymentSetting.tpl.htm\");"
"{MODULES_PATH}admin/classes/admin/settings_interface.php","replace","$this->ObTpl->set_var(\"TPL_VAR_PAYPALAPI_USERNAME\", PAYPALAPI_USERNAME);\n\t\t$this->ObTpl->set_var(\"TPL_VAR_PAYPALAPI_PASSWORD\", PAYPALAPI_PASSWORD);\n\t\t$this->ObTpl->set_var(\"TPL_VAR_PAYPALAPI_SIGNATURE\", PAYPALAPI_SIGNATURE);\n\t\t$this->ObTpl->set_var(\"TPL_VAR_PAYPALAPI_ENDPOINT\", PAYPALAPI_ENDPOINT);","$this->ObTpl->set_var(\"TPL_VAR_PAYPALAPI_USERNAME\", PAYPALAPI_USERNAME);\n\t\t$this->ObTpl->set_var(\"TPL_VAR_PAYPALAPI_PASSWORD\", PAYPALAPI_PASSWORD);\n\t\t$this->ObTpl->set_var(\"TPL_VAR_PAYPALAPI_SIGNATURE\", PAYPALAPI_SIGNATURE);\n\t\t$this->ObTpl->set_var(\"TPL_VAR_PAYPALAPI_ENDPOINT\", PAYPALAPI_ENDPOINT);\n\t\t\n\t\t$this->ObTpl->set_var(\"TPL_VAR_WP_MC\",WorldPayMerchantCode);\n\t\t$this->ObTpl->set_var(\"TPL_VAR_WP_PW\",WorldPayPass);\n\t\t$this->ObTpl->set_var(\"TPL_VAR_WP_II\",WorldPayInstallationId);\n\t\t$this->ObTpl->set_var(\"TPL_VAR_WP_CC\",WorldPayCurrencyCode);\n\t\t$this->ObTpl->set_var(\"TPL_VAR_WPH_ID\",WorldPayRedirectInstallationId);\n\t\t$this->ObTpl->set_var(\"TPL_VAR_WPH_CUR\",WorldPayRedirectCurrencyCode);"
"{ADMIN_THEME_PATH}admin/templates/admin/paymentSetting.tpl.htm","replace","<option value=\"securetrading\">Secure Trading</option>","<option value=\"securetrading\">Secure Trading</option><option value=\"worldpay\">World Pay</option>"
"{ADMIN_THEME_PATH}admin/templates/admin/paymentSetting.tpl.htm","replace","<div class=\"AccordionPanel\">\n\t\t\t\t  <div class=\"AccordionPanelTab colaspTitle2\"><img src=\"../images/collasp-arrow.png\" alt=\"collasp\" /><div>Cardsave Direct</div></div>\n\t\t\t\t\t<div class=\"AccordionPanelContent colaspWrapper colaspContent2\">\n\t\t\t\t\t<table class='whiteTable2'>\n\t\t\t\t\t\t\t<thead>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<th colspan=\"2\">CardSave Direct<a href=\"https://mms.cardsaveonlinepayments.com/\" class=\"linkSignup\" target=\"_blank\">Signup Now</a> (link opens in a new window)</th>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t</thead>\n\t\t\t\t\t\t\t<tbody>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Merchant ID</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardSaveID\" name=\"txtCardSaveID\" value=\"{TPL_VAR_CS_ID}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Password</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardSavePass\" name=\"txtCardSavePass\" value=\"{TPL_VAR_CS_PASS}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Payment Processor Domain</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardSaveURL\" name=\"txtCardSaveURL\" value=\"{TPL_VAR_CS_DOMAIN}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Payment Processor Port</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardSavePort\" name=\"txtCardSavePort\" value=\"{TPL_VAR_CS_PORT}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Preshared Key</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardSaveKey\" name=\"txtCardSaveKey\" value=\"{TPL_VAR_CS_SECRET}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Currency</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardSaveCurrency\" name=\"txtCardSaveCurrency\" value=\"{TPL_VAR_CS_CURRENCY}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t</tbody>\t\n\t\t\t\t\t\t</table>\n\t\t\t\t\t</div>\n\t\t\t\t</div> ","<div class=\"AccordionPanel\">\n\t\t\t\t  <div class=\"AccordionPanelTab colaspTitle2\"><img src=\"../images/collasp-arrow.png\" alt=\"collasp\" /><div>Cardsave Direct</div></div>\n\t\t\t\t\t<div class=\"AccordionPanelContent colaspWrapper colaspContent2\">\n\t\t\t\t\t<table class='whiteTable2'>\n\t\t\t\t\t\t\t<thead>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<th colspan=\"2\">CardSave Direct<a href=\"https://mms.cardsaveonlinepayments.com/\" class=\"linkSignup\" target=\"_blank\">Signup Now</a> (link opens in a new window)</th>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t</thead>\n\t\t\t\t\t\t\t<tbody>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Merchant ID</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardSaveID\" name=\"txtCardSaveID\" value=\"{TPL_VAR_CS_ID}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Password</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardSavePass\" name=\"txtCardSavePass\" value=\"{TPL_VAR_CS_PASS}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Payment Processor Domain</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardSaveURL\" name=\"txtCardSaveURL\" value=\"{TPL_VAR_CS_DOMAIN}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Payment Processor Port</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardSavePort\" name=\"txtCardSavePort\" value=\"{TPL_VAR_CS_PORT}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Preshared Key</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardSaveKey\" name=\"txtCardSaveKey\" value=\"{TPL_VAR_CS_SECRET}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Currency</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardSaveCurrency\" name=\"txtCardSaveCurrency\" value=\"{TPL_VAR_CS_CURRENCY}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t</tbody>\t\n\t\t\t\t\t\t</table>\n\t\t\t\t\t</div>\n\t\t\t\t</div> <div class=\"AccordionPanel\">\n\t\t\t\t  <div class=\"AccordionPanelTab colaspTitle2\"><img src=\"../images/collasp-arrow.png\" alt=\"collasp\" /><div>WorldPay Direct</div></div>\n\t\t\t\t\t<div class=\"AccordionPanelContent colaspWrapper colaspContent2\">\n\t\t\t\t\t<table class='whiteTable2'>\n\t\t\t\t\t\t\t<thead>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<th colspan=\"2\">WorldPay Direct<a href=\"http://worldpay.com/\" class=\"linkSignup\" target=\"_blank\">Signup Now</a> (link opens in a new window)</th>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t</thead>\n\t\t\t\t\t\t\t<tbody>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Merchant Code</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtWorldPayMercCode\" name=\"txtWorldPayMercCode\" value=\"{TPL_VAR_WP_MC}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Password</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtWorldPayPass\" name=\"txtWorldPayPass\" value=\"{TPL_VAR_WP_PW}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Install Id</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtWorldPayInstall\" name=\"txtWorldPayInstall\" value=\"{TPL_VAR_WP_II}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Currency Code</label></td>\n\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtWorldPayCurrencyCode\" name=\"txtWorldPayCurrencyCode\" value=\"{TPL_VAR_WP_CC}\" /></td>\n\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t</tbody>\t\n\t\t\t\t\t\t</table>\n\t\t\t\t\t</div>\n\t\t\t\t</div> "
"{ADMIN_THEME_PATH}admin/templates/admin/paymentSetting.tpl.htm","replace","<div class=\"AccordionPanel\">\n\t\t\t\t\t\t\t  <div class=\"AccordionPanelTab colaspTitle2\"><img src=\"../images/collasp-arrow.png\" alt=\"collasp\" /><div>CardSave Redirect</div></div>\n\t\t\t\t\t\t\t\t<div class=\"AccordionPanelContent colaspWrapper colaspContent2\" >\n\t\t\t\t\t\t\t\t<table class='whiteTable2'>\n\t\t\t\t\t\t\t\t\t\t<thead>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<th colspan=\"2\">CardSave Redirect<a href=\"https://mms.cardsaveonlinepayments.com/\" class=\"linkSignup\" target=\"_blank\">Signup Now</a> (link opens in a new window)</th>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t</thead>\n\t\t\t\t\t\t\t\t\t\t<tbody>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Merchant ID</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSaveMerchantID\" name=\"txtCardRSaveMerchantID\" value=\"{TPL_VAR_CSR_ID}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Merchant Password</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSavePassword\" name=\"txtCardRSavePassword\" value=\"{TPL_VAR_CSR_PASS}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Secret Key</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSaveKey\" name=\"txtCardRSaveKey\" value=\"{TPL_VAR_CSR_SECRET}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Payment Processor Domain</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSaveDomain\" name=\"txtCardRSaveDomain\" value=\"{TPL_VAR_CSR_DOMAIN}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Results</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSaveResults\" name=\"txtCardRSaveResults\" value=\"{TPL_VAR_CSR_RESULTS}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>CV2</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSaveCV2\" name=\"txtCardRSaveCV2\" value=\"{TPL_VAR_CSR_CV2}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Currency</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSaveCurrency\" name=\"txtCardRSaveCurrency\" value=\"{TPL_VAR_CSR_CURRENCY}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t</tbody>\t\n\t\t\t\t\t\t\t\t\t</table>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>  ","<div class=\"AccordionPanel\">\n\t\t\t\t\t\t\t  <div class=\"AccordionPanelTab colaspTitle2\"><img src=\"../images/collasp-arrow.png\" alt=\"collasp\" /><div>CardSave Redirect</div></div>\n\t\t\t\t\t\t\t\t<div class=\"AccordionPanelContent colaspWrapper colaspContent2\" >\n\t\t\t\t\t\t\t\t<table class='whiteTable2'>\n\t\t\t\t\t\t\t\t\t\t<thead>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<th colspan=\"2\">CardSave Redirect<a href=\"https://mms.cardsaveonlinepayments.com/\" class=\"linkSignup\" target=\"_blank\">Signup Now</a> (link opens in a new window)</th>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t</thead>\n\t\t\t\t\t\t\t\t\t\t<tbody>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Merchant ID</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSaveMerchantID\" name=\"txtCardRSaveMerchantID\" value=\"{TPL_VAR_CSR_ID}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Merchant Password</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSavePassword\" name=\"txtCardRSavePassword\" value=\"{TPL_VAR_CSR_PASS}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Secret Key</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSaveKey\" name=\"txtCardRSaveKey\" value=\"{TPL_VAR_CSR_SECRET}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Payment Processor Domain</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSaveDomain\" name=\"txtCardRSaveDomain\" value=\"{TPL_VAR_CSR_DOMAIN}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Results</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSaveResults\" name=\"txtCardRSaveResults\" value=\"{TPL_VAR_CSR_RESULTS}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>CV2</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSaveCV2\" name=\"txtCardRSaveCV2\" value=\"{TPL_VAR_CSR_CV2}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Currency</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtCardRSaveCurrency\" name=\"txtCardRSaveCurrency\" value=\"{TPL_VAR_CSR_CURRENCY}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t</tbody>\t\n\t\t\t\t\t\t\t\t\t</table>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>  <div class=\"AccordionPanel\">\n\t\t\t\t\t\t\t  <div class=\"AccordionPanelTab colaspTitle2\"><img src=\"../images/collasp-arrow.png\" alt=\"collasp\" /><div>WorldPay Hosted</div></div>\n\t\t\t\t\t\t\t\t<div class=\"AccordionPanelContent colaspWrapper colaspContent2\" >\n\t\t\t\t\t\t\t\t<table class='whiteTable2'>\n\t\t\t\t\t\t\t\t\t\t<thead>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<th colspan=\"2\">WorldPay Hosted<a href=\"http://worldpay.com/\" class=\"linkSignup\" target=\"_blank\">Signup Now</a> (link opens in a new window)</th>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t</thead>\n\t\t\t\t\t\t\t\t\t\t<tbody>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Installation ID</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtWorldPayHMID\" name=\"txtWorldPayHMID\" value=\"{TPL_VAR_WPH_ID}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t\t\t\t\t<td class=\"first\"><label>Currency Code</label></td>\n\t\t\t\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"formField\" id=\"txtWorldPayHCUR\" name=\"txtWorldPayHCUR\" value=\"{TPL_VAR_WPH_CUR}\" /></td>\n\t\t\t\t\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t\t\t\t</tbody>\t\n\t\t\t\t\t\t\t\t\t</table>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>  "
"{MODULES_PATH}ecom/ecom_controller.php","replace","$obBill->billShipTemplate=$this->templatePath.\"ConfirmOrderAndBillShip.tpl.htm\"","$pluginInterface = new pluginInterface();\n$obBill->billShipTemplate=$pluginInterface->plugincheck($this->templatePath.\"ConfirmOrderAndBillShip.tpl.htm\")"
"{MODULES_PATH}ecom/ecom_controller.php","replace","include_once($pluginInterface->plugincheck(MODULES_PATH.\"ecom/classes/main/cardSave.php\"));","include_once($pluginInterface->plugincheck(MODULES_PATH.\"ecom/classes/main/cardSave.php\")); \ninclude_once($pluginInterface->plugincheck(SITE_PATH.\"plugins/WorldPay/WorldPay.php\")); "
"{MODULES_PATH}ecom/ecom_controller.php","replace","case \"cshcb2\":\n					$cardSave->m_cardSave_Hosted_Callback(\"1\");\n				break;","case \"cshcb2\":\n					$cardSave->m_cardSave_Hosted_Callback(\"1\");\n				break;\n				case \"wpcb\":\n								$worldPay = new WorldPay();\n			$worldPay->obDb=$this->obDb;\n			$worldPay->obTpl=$this->obTpl;\n			$worldPay->request=$this->request;\n			$worldPay->libFunc=$this->libFunc;\n			$worldPay->WorldPayCallback();\n				break;"
"{MODULES_PATH}ecom/classes/main/billShipInfo.php","replace","$this->ObTpl->set_block(\"TPL_USER_FILE\",\"TPL_FREEPOST_BLK\",\"freepost_blk\");","$this->ObTpl->set_block(\"TPL_USER_FILE\",\"TPL_WORLDPAY_BLK\",\"worldpay_blk\");\n\n		$this->ObTpl->set_block(\"TPL_USER_FILE\",\"TPL_FREEPOST_BLK\",\"freepost_blk\");"
"{MODULES_PATH}ecom/classes/main/billShipInfo.php","replace","$this->ObTpl->set_var(\"freepost_blk\",\"\");","$this->ObTpl->set_var(\"worldpay_blk\",\"\");\n		$this->ObTpl->set_var(\"freepost_blk\",\"\");"
"{MODULES_PATH}ecom/classes/main/billShipInfo.php","replace"," #(BEGIN) SAGE PAY INTERGRATION\n             if (SAGE_VENDORNAME!=\"\" && SAGE_ENCRYPTEDPASSWORD!=\"\" && SAGE_TRANSACTIONTYPE!=\"\" && SAGE_CURRENCY!=\"\" )\n			{\n				$blkactive=1;\n				$this->ObTpl->parse(\"sagepayform_blk\",\"TPL_SAGEPAYFORM_BLK\");\n			}\n            #(BEGIN) SAGE PAY INTERGRATION"," #(BEGIN) SAGE PAY INTERGRATION\n             if (SAGE_VENDORNAME!=\"\" && SAGE_ENCRYPTEDPASSWORD!=\"\" && SAGE_TRANSACTIONTYPE!=\"\" && SAGE_CURRENCY!=\"\" )\n			{\n				$blkactive=1;\n				$this->ObTpl->parse(\"sagepayform_blk\",\"TPL_SAGEPAYFORM_BLK\");\n			}\n            #(BEGIN) SAGE PAY INTERGRATION\n            if(WORLDPAY_ONOFF == 1)\n			{\n				$blkactive=1;\n				$this->ObTpl->parse(\"worldpay_blk\",\"TPL_WORLDPAY_BLK\");\n			}"
"{MODULES_PATH}ecom/classes/main/saveOrder.php","replace","case \"Cardsave\":\n\t\t\t\t\t\t$cardSave = new c_cardSave($this->orderId);\n\t\t\t\t\t\t$cardSave->obDb=$this->obDb;\n\t\t\t\t\t\t$cardSave->obTpl=$this->obTpl;\n\t\t\t\t\t\t$cardSave->request=$this->request;\n\t\t\t\t\t\t$cardSave->libFunc=$this->libFunc;\n\t\t\t\t\t\t$cardSave->m_CardSave_Direct();\n\t\t\t\t\t\texit;\n\t\t\t\t\tbreak;","case \"Cardsave\":\n\t\t\t\t\t\t$cardSave = new c_cardSave($this->orderId);\n\t\t\t\t\t\t$cardSave->obDb=$this->obDb;\n\t\t\t\t\t\t$cardSave->obTpl=$this->obTpl;\n\t\t\t\t\t\t$cardSave->request=$this->request;\n\t\t\t\t\t\t$cardSave->libFunc=$this->libFunc;\n\t\t\t\t\t\t$cardSave->m_CardSave_Direct();\n\t\t\t\t\t\texit;\n\t\t\t\t\tbreak;\n				case \"worldpay\":\n					$worldPay = new WorldPay();\n					$worldPay->obDb=$this->obDb;\n					$worldPay->obTpl=$this->obTpl;\n					$worldPay->request=$this->request;\n					$worldPay->libFunc=$this->libFunc;\n					$worldPay->DirectPayment();\n				exit;\n	break;"
"{MODULES_PATH}ecom/classes/main/saveOrder.php","replace","case \"cs_redirect\":\n					$cardSave = new c_cardSave($this->orderId);\n					$cardSave->obDb=$this->obDb;\n					$cardSave->obTpl=$this->obTpl;\n					$cardSave->request=$this->request;\n					$cardSave->libFunc=$this->libFunc;\n					$cardSave->m_CardSave_Hosted();\n					exit;\n				break;","case \"cs_redirect\":\n					$cardSave = new c_cardSave($this->orderId);\n					$cardSave->obDb=$this->obDb;\n					$cardSave->obTpl=$this->obTpl;\n					$cardSave->request=$this->request;\n					$cardSave->libFunc=$this->libFunc;\n					$cardSave->m_CardSave_Hosted();\n					exit;\n				break;\n				case \"worldpay\":\n					$worldPay = new WorldPay();\n					$worldPay->obDb=$this->obDb;\n					$worldPay->obTpl=$this->obTpl;\n					$worldPay->request=$this->request;\n					$worldPay->libFunc=$this->libFunc;\n					$worldPay->RedirectForm();\n				exit;\n	break;"
"{THEME_PATH}ecom/templates/main/ConfirmOrderAndBillShip.tpl.htm","replace","<!-- (END) SAGEPAY INTEGRATION -->","<!-- (END) SAGEPAY INTEGRATION -->\n			<!-- BEGIN TPL_WORLDPAY_BLK -->\n            <div class=\"billShipBlock\">\n				<h3><input id=\"worldpayform\" type=\"radio\" name=\"paymethod\" class=\"formRadio\" value=\"worldpay\" />Pay by Credit/Debit card using WorldPay</h3>\n				<p><img class=\"paymentGateway\" src=\"{TPL_VAR_SITEURL}/plugins/WorldPay/poweredByWorldPay.gif\" alt=\"WorldPay Logo.\" /> You will be taken to the <strong>WorldPay</strong> website to make your payment.</p>\n			</div>\n			<!-- END TPL_WORLDPAY_BLK -->"
<?php if (!defined("TL_ROOT")) die("You can not access this file directly!");

/**
 * PHP version 5
 * @copyright  hh,mm 2014
 * @author     Harald Huber <hh@weblifting.at>
 * @author     Martin Mayr <mm@weblifting.at>
 * @package    isotope_payment_masterpayment
 * @license	   GPL
 */
 

/*
 * legend
 */
$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentSettings_legend"] = "Masterpayment Einstellungen";


/*
 * fields
 */
$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentServer"] 			= array("Serveradresse", "Adresse des Bezahlserver (zB:https://www.masterpayment.com/de/tools/testgateway)");
$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentMerchantLogin"] 	= array("H&auml;ndler ID", "Die H&auml;ndler ID/Merchant Login bzw. Merchant Name hier eintragen.");

$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentSecretKey"] 			= array("SecretKey", "SecretKey hier eintragen.");
$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentBasketDescription"] 			= array("Warenkorb Beschreibung", "Warenkorbbeschreibung hier eintragen. Die Beschreibung ist auf der Kreditkarten-Abrechung des Kunden ersichtlich.");

$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentGatewayStyle"] 			= array("Gateway Style", "Gateway Style ausw&auml;hlen.");

$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentGatewayStyleOptions"]	= array(
																								"standard" => "Standardanzeige", 
																								"pinmobile" => "PinMobile", 
																								"mobile" => "Mobile"
																					   );	
																					   
$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentAsiFrame"] 			= array("Iframe", "Als iFrame einbinden.");
$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentPaymentType"] 			= array("Bezahlart", "Zahlungsart ausw&auml;hlen.");

$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentPaymentTypeOptions"]	= array(
																								"none" => "Keine Angabe", 
																								"credit_card" => "Kreditkarte", 
																								"elv" => "Lastschrift"
																					   );
$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentUrlPatternSuccess"] 			= array("UrlPatternSuccess", "");																	   
$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentUrlPatternFailure"] 			= array("UrlPatternFailure", "");																	   
$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentUrlRedirectSuccess"] 			= array("UrlRedirectSuccess", "");																	   
$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentUrlRedirectCancel"] 			= array("UrlRedirectCancel", "");																	   
$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentUrlRedirectFailure"] 			= array("UrlRedirectFailure", "");																	   
?>
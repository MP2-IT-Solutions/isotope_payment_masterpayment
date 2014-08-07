<?php if (!defined("TL_ROOT")) die("You can not access this file directly!");

/**
 * PHP version 5
 * @copyright  hh,mm 2014
 * @author     Martin Mayr <mm@weblifting.at>
 * @author     Harald Huber <hh@weblifting.at>
 * @package    isotope_payment_masterpayment
 * @license	   GPL
 */
 

/*
 * palette
 */
$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["palettes"]["masterpayment"] = "{type_legend},name,label,type;{note_legend:hide},note;{config_legend},new_order_status,minimum_total,maximum_total,countries,shipping_modules,product_types;{price_legend:hide},price,tax_class;

{masterpaymentSettings_legend},masterpaymentServer,masterpaymentMerchantLogin,masterpaymentSecretKey,masterpaymentBasketDescription,masterpaymentPaymentType,masterpaymentGatewayStyle, masterpaymentAsiFrame;
{expert_legend:hide},guests,protected;{enabled_legend},enabled";


/*
 * fields
 Textfeld: masterpaymentServer
 Textfeld: merchantLogin == merchantName
 Textfeld: SecretKey
 Select paymentType
 Select gatewayStyle
 Textfeld: basketDescription
 */
 



$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["fields"]["masterpaymentServer"] = array(
	"label"						=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentServer"],
	"default"					=> "",
	"exclude"					=> true,
	"inputType"					=> "text",
	"eval"						=> array("mandatory" => true, "tl_class" => "w50")
);

$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["fields"]["masterpaymentMerchantLogin"] = array(
	"label"						=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentMerchantLogin"],
	"default"					=> "",
	"exclude"					=> true,
	"inputType"					=> "text",
	"eval"						=> array("mandatory" => true, "tl_class" => "w50")
);

$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["fields"]["masterpaymentSecretKey"] = array(
	"label"						=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentSecretKey"],
	"default"					=> "",
	"exclude"					=> true,
	"inputType"					=> "text",
	"eval"						=> array("mandatory" => true, "tl_class" => "w50")
);

$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["fields"]["masterpaymentBasketDescription"] = array(
	"label"						=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentBasketDescription"],
	"default"					=> "",
	"exclude"					=> true,
	"inputType"					=> "text",
	"eval"						=> array("mandatory" => true, "tl_class" => "w50")
);

$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["fields"]["masterpaymentAsiFrame"] = array(
	'label'					=> &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['masterpaymentAsiFrame'],
	'exclude'				=> true,
	'filter'				=> true,
	'inputType'				=> 'checkbox',
	'eval'					=> array('tl_class'=>'w50')
);

$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["fields"]["masterpaymentPaymentType"] = array(
	"label"						=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentPaymentType"],
	#"options"					=> array("none","credit_card", "elv"),
	"options"					=> array("none","credit_card"),
	"default"					=> "none",
	"reference"					=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentPaymentTypeOptions"],
	"exclude"					=> true,
	"inputType"					=> "select",
	"eval"						=> array("mandatory" => true, "tl_class" => "w50")
);

$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["fields"]["masterpaymentGatewayStyle"] = array(
	"label"						=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentGatewayStyle"],
	#"options"					=> array("standard","pinmobile", "mobile"),
	"options"					=> array("standard"),
	"default"					=> "standard",
	"reference"					=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentGatewayStyleOptions"],
	"exclude"					=> true,
	"inputType"					=> "select",
	"eval"						=> array("mandatory" => true, "tl_class" => "w50")
);

$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["fields"]["masterpaymentUrlPatternSuccess"] = array(
	"label"						=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentUrlPatternSuccess"],
	"default"					=> "",
	"exclude"					=> true,
	"inputType"					=> "text",
	"eval"						=> array("mandatory" => true, "tl_class" => "w50")
);

$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["fields"]["masterpaymentUrlPatternFailure"] = array(
	"label"						=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentUrlPatternFailure"],
	"default"					=> "",
	"exclude"					=> true,
	"inputType"					=> "text",
	"eval"						=> array("mandatory" => true, "tl_class" => "w50")
);

$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["fields"]["masterpaymentUrlRedirectSuccess"] = array(
	"label"						=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentUrlRedirectSuccess"],
	"default"					=> "",
	"exclude"					=> true,
	"inputType"					=> "text",
	"eval"						=> array("mandatory" => true, "tl_class" => "w50")
);

$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["fields"]["masterpaymentUrlRedirectCancel"] = array(
	"label"						=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentUrlRedirectCancel"],
	"default"					=> "",
	"exclude"					=> true,
	"inputType"					=> "text",
	"eval"						=> array("mandatory" => true, "tl_class" => "w50")
);

$GLOBALS["TL_DCA"]["tl_iso_payment_modules"]["fields"]["masterpaymentUrlRedirectFailure"] = array(
	"label"						=> &$GLOBALS["TL_LANG"]["tl_iso_payment_modules"]["masterpaymentUrlRedirectFailure"],
	"default"					=> "",
	"exclude"					=> true,
	"inputType"					=> "text",
	"eval"						=> array("mandatory" => true, "tl_class" => "w50")
);
class PaymentSofortueberweisungIsoPaymentModuleXXXX extends Backend {
	/**
	 * call parent constructor
	 */
	public function __construct() {
		parent::__construct();
	}
	
	
	/**
	 * replace umlauts
	 */
	public function reason($strValue, DataContainer $dc) {
		return utf8_romanize($strValue);
	}
}
	
?>
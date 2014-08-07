-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the Contao    *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************


-- 
-- Table `tl_module`
-- 


CREATE TABLE `tl_iso_payment_modules` (
  `masterpaymentServer` varchar(255) NOT NULL default '',
  `masterpaymentMerchantLogin` varchar(255) NOT NULL default '',
  `masterpaymentSecretKey` varchar(255) NOT NULL default '',
  `masterpaymentBasketDescription` varchar(255) NOT NULL default '',
  `masterpaymentPaymentType` varchar(255) NOT NULL default '',
  `masterpaymentGatewayStyle` varchar(255) NOT NULL default '',
  `masterpaymentAsiFrame` varchar(1) NOT NULL default '0'
  `masterpaymentUrlPatternSuccess` varchar(255) NOT NULL default '',
  `masterpaymentUrlPatternFailure` varchar(255) NOT NULL default '',
  `masterpaymentUrlRedirectSuccess` varchar(255) NOT NULL default '',
  `masterpaymentUrlRedirectCancel` varchar(255) NOT NULL default '',
  `masterpaymentUrlRedirectFailure` varchar(255) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
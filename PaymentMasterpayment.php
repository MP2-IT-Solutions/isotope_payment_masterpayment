<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * PHP version 5
 * @copyright  hh, mm 2014
 * @author     Harald Huber <hh@weblifting.atm>
 * @author     Martin Mayr <mm@weblifting.atm>
 * @package    isotope_payment_masterpayment
 * @license	   GPL
 */
 
 
class PaymentMasterpayment extends IsotopePayment {



	public function checkoutForm() {
		$this->import('Isotope');
		
		$objOrder = new IsotopeOrder();

		if (!$objOrder->findBy('cart_id', $this->Isotope->Cart->id))
			$this->redirect($this->addToUrl('step=failed', true));
		
		// MasterPayment Server URL
		$masterpaymentServer = trim($this->masterpaymentServer);
		
		
		$objTemplate = new FrontendTemplate("iso_payment_masterpayment");
		$objTemplate->h2				= $GLOBALS['TL_LANG']['MSC']['pay_with_redirect'][0];
		$objTemplate->message			= $GLOBALS['TL_LANG']['MSC']['pay_with_redirect'][1];
		$objTemplate->invocationUrl		= $masterpaymentServer;
		$parameterArray = $this->getAllParameter($objOrder, $masterpaymentServer);
		$objTemplate->params			= $parameterArray['parameter'];
		$objTemplate->submit			= specialchars($GLOBALS['TL_LANG']['MSC']['pay_with_redirect'][2]);
		
		$objTemplate->alsIFrame	= $this->masterpaymentAsiFrame;
		$objTemplate->iFrameString	= $parameterArray['forFrame'];

		//Parse Template...
		return $objTemplate->parse();
	}
	
	
	/**
	 * process payment function
	 * Wird ausgeführt wenn die Bezahlung beim Payment Provider abgeschlossen wurde.
	 
	 */
	public function processPayment() 
	{
		$objOrder = new IsotopeOrder();
		
		if (!$objOrder->findBy('cart_id', $this->Isotope->Cart->id))
		{
			return false;
		}
	
		$parameterArray = $this->getAllParameter($objOrder, trim($this->masterpaymentServer));
		$status = $this->checkStatusAtMasterpayment($parameterArray['parameter'] ,('https://masterpayment.com/services/rest/txservices/status'));

		if ($this->Input->get("i") == "urs" && $status->STATUS == "SUCCESS"  && $status->TX_ID == $objOrder->uniqid )
		{
			// Bestellung Erfolgreich (Success)
			$this->log('Bezahlung erfolgreich durchgef&uuml;hrt! OrderId: '.$objOrder->id , __METHOD__, TL_GENERAL);
			$objOrder->updateOrderStatus($this->new_order_status);
			$this->Database->prepare("UPDATE tl_iso_orders SET date_paid=" . time() . " WHERE id=? ")->execute($objOrder->id);
			return true;
			
			
			#$this->log('Evtl. Manipulationsversuch bei Bezahlung! OrderId: '.$objOrder->id, __METHOD__, TL_ERROR);
			#return false;
			
		}
		elseif ($this->Input->get("i") == "urc" && $status->STATUS == "SUCCESS"  && $status->TX_ID == $objOrder->uniqid )
		{
			// Bestellung abgebrochen (Cancel)
			$this->log('Bezahlung wurde abgebrochen! OrderId: '.$objOrder->id, __METHOD__, TL_ERROR);
			return false;
		}
		elseif ($this->Input->get("i") == "urf" && $status->STATUS == "FAILED" )
		{
			// Bestellung fehlerhaft (Failure)
			
			#var_dump($status);
			#var_dump($parameterArray['parameter']);
			#exit;
			$this->log('Bezahlvorgang fehlerhaft (falsche Daten eingetragen)! OrderId: '.$objOrder->id , __METHOD__, TL_ERROR);
			return false;
		}
		else
		{
			// Bestellung fehlerhaft (Failure)
			$this->log('Bezahlvorgang fehlerhaft! (Fehler unbekannt!) OrderId: '.$objOrder->id, __METHOD__, TL_ERROR);
			return false;
		}
		
		if ($objOrder->date_paid > 0 && $objOrder->date_paid <= time())
		{
			IsotopeFrontend::clearTimeout();
			return true;
		}

		if (IsotopeFrontend::setTimeout()) {
			// Do not index or cache the page
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			
			$objTemplate = new FrontendTemplate('mod_message');
			$objTemplate->type = 'processing';
			$objTemplate->message = $GLOBALS['TL_LANG']['MSC']['payment_processing'];
			return $objTemplate->parse();
		}

		$this->log('Payment could not be processed.', __METHOD__, TL_ERROR);

	}
	
	
	/**
	 * process sofortuebewerweisung "Benachrichtigung"
	 * Checkt Status vom Bezahlanbieter...
	 * 
	 */
	public function processPostSale() {
		// get get-variables
		$intOrderId = $this->Input->get("uv1");
		$this->log("Order " . $intOrderId . " successfully payed (hashes match).", "PaymentSofortueberweisung processPostSale()", TL_GENERAL);
		
		// update database
		$this->Database->prepare("UPDATE tl_iso_orders SET date_paid = ? WHERE id = ?")->execute(time(), $intOrderId);
		
		// exit with header 200
		header('HTTP/1.1 200 OK');
		exit;
	}
	
	
	/**
	 * show payment information in backend
	 */	
	public function backendInterface($intOrderId) {
		$objOrder = $this->Database->prepare("SELECT date_paid FROM tl_iso_orders WHERE id = ?")->execute($intOrderId);
		
		$objTemplate = new BackendTemplate("be_sofortueberweisung");
		
		$objTemplate->href	= $this->getReferer(true);
		$objTemplate->title	= specialchars($GLOBALS['TL_LANG']['MSC']['backBT']);
		$objTemplate->link	= $GLOBALS['TL_LANG']['MSC']['backBT'];
		$objTemplate->h2	= sprintf($GLOBALS['TL_LANG']['ISO']['masterpaymentH2'], $intOrderId);
		$objTemplate->label	= $GLOBALS['TL_LANG']['ISO']['masterpaymentLabel'];
		$objTemplate->text	= strlen($objOrder->date_paid) ? sprintf($GLOBALS['TL_LANG']['ISO']['masterpaymentText']['done'], $this->parseDate($GLOBALS["TL_CONFIG"]["dateFormat"], $objOrder->date_paid), $this->parseDate($GLOBALS["TL_CONFIG"]["timeFormat"], $objOrder->date_paid)) : $GLOBALS["TL_LANG"]["ISO"]["masterpaymentText"]["open"];
		
		return $objTemplate->parse();
	}
	
	private function checkStatusAtMasterpayment($request_data, $server)
	{
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($request_data),
			),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($server, false, $context);
		$statusXML = simplexml_load_string($result);
		#var_dump($statusXML);
		
		return($statusXML);
	}
	
	private function getAllParameter($objOrder, $masterpaymentServer)
	{
		$this->import('Isotope');
		// login of the merchant
		$merchantLogin = trim($this->masterpaymentMerchantLogin);
		$merchantSecretKey = $this->masterpaymentSecretKey;
		//set appended params in url even if value is empty
		$appendedParams = array(
				"merchantName" => true,
				"txId" => true, //
				"basketDescription" => true,
				"basketValue" => true,
				"currency" => true,
				"language" => true,
				"userId" => true,
				"firstname" => true,
				"lastname" => true,
				"street" => true,
				"zipCode" => true,
				"city" => true,
				"country" => true,
				"email" => true,
				"userIp" => true,
				"paymentType" => true,
				"sex" => true,
				"gatewayStyle" => true,
				"UrlPatternSuccess" => false,
				"UrlPatternFailure" => false,
				"UrlRedirectSuccess" => false,
				"UrlRedirectCancel" => false,
				"UrlRedirectFailure" => false
		);

		//User ID wenn eingeloggt sonst Name
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$userId = $objOrder->billing_address['firstname']."-".$objOrder->billing_address['lastname']."-KN".$this->User->id;
		}
		else
		{
			$userId = $objOrder->billing_address['firstname']."-".$objOrder->billing_address['lastname']."-KN".$objOrder->id;
		}
		
		// set params values
		$params = array();
		$params['merchantName'] = trim($this->masterpaymentMerchantLogin);
		$params['paymentType'] = $this->masterpaymentPaymentType;
		$params['gatewayStyle'] = $this->masterpaymentGatewayStyle;
		$params['language'] = strtoupper($objOrder->language);
		
		$params['currency'] = $this->Isotope->Config->currency ? $this->Isotope->Config->currency : "EUR";
		$params['basketValue'] = intval($objOrder->grandTotal*100); // Angabe in CENT
		//please update your transaction id everytime
		$params['txId'] = $objOrder->uniqid; //uniqid != integer laut mp darfs zwar string sein, funktioniert aber nicht...
		$params['basketDescription'] = $this->masterpaymentBasketDescription;
		$params['userId'] = str_replace(' ', '-', $userId);
		$params['operation'] = 'DB';
		$params['sex'] = 'unknown';
		$params['firstname'] = ($objOrder->billing_address['firstname']);
		$params['lastname'] = $objOrder->billing_address['lastname'];
		$params['street'] = $objOrder->billing_address['street_1'];
		$params['zipCode'] = $objOrder->billing_address['postal'];
		$params['city'] = $objOrder->billing_address['city'];
		$params['country'] = $objOrder->billing_address['country'] ? strtoupper ($objOrder->billing_address['country']) : "DE";
		$params['email'] = $objOrder->billing_address['email'];
		$params['userIp'] = $this->Environment->ip;
		$params['showCancelOption'] = true;
		
		$baseUrlLang = "target-parent:".$this->Environment->base.$this->replaceInsertTags( '{{page::alias}}' )."/step/";
		// == "https://www.premium-guards.com/de/kassa/step/"	
		
		#$params['UrlPatternSuccess'] = $baseUrlLang."complete.html?i=ups&e=".$controll."&u=".$objOrder->uniqid."&txId=\${TX_ID}&status=\${STATUS}";
		$params['UrlPatternSuccess'] = $baseUrlLang."complete.html?i=ups";
		
		#$params['UrlPatternFailure'] = $baseUrlLang."complete.html?i=upf&e=".$controll."&u=".$objOrder->uniqid."&txId=\${TX_ID}&status=\${STATUS}";
		$params['UrlPatternFailure'] = $baseUrlLang."complete.html?i=upf";
		
		#$params['UrlRedirectSuccess'] = $baseUrlLang."complete.html?i=urs&e=".$controll."&u=".$objOrder->uniqid."&status=\${STATUS}"; // bei erfolgreicher bezahlung
		$params['UrlRedirectSuccess'] = $baseUrlLang."complete.html?i=urs"; // bei erfolgreicher bezahlung
		
		#$params['UrlRedirectCancel'] = $baseUrlLang."complete.html?i=urc&e=".$controll."&u=".$objOrder->uniqid."&status=\${STATUS}";
		$params['UrlRedirectCancel'] = $baseUrlLang."complete.html?i=urc";
		
		#$params['UrlRedirectFailure'] = $baseUrlLang."complete.html?i=urf&e=".$controll."&u=".$objOrder->uniqid."&status=\${STATUS}";
		$params['UrlRedirectFailure'] = $baseUrlLang."complete.html?i=urf";
		
		#$params['disableEmailForm'] = 'false';
		#$params['showCancelOption'] = 'true';
		
		/*
		TestAccount bei masterpayment.com
		Login auf mp:
		Username: developer@masterpayment.com
		Passwort: 12345678
		Bezahlung auf dem Testsystem mit Echtgeld!!
		*//*
		if (false)
		{
			$params['merchantName'] = "developer@masterpayment.com";
			$merchantSecretKey = "a6d7439d-ffb8-4ff2-a510-836da3bba2f7";
		}
		*/
		
		// sort array keys
		$sortedParams = $params;
		uksort($sortedParams, 'strcmp');
		// control key parameter computation
		$controlKeyString = '';
		$separator = '|';
		foreach($sortedParams as $paramName => $paramValue) {
			if(!empty($paramValue)) {
				$controlKeyString .=   ($paramValue) . $separator ;
			} elseif($appendedParams[$paramName]){
				$controlKeyString .= $separator;
			}
		}
		//assign merchant secret key
		$controlKeyString .= $merchantSecretKey;
		//coumpute md5
		$controlKey = md5($controlKeyString);
		$params['controlKey'] = $controlKey;
		// gateway invocation URL computation (invocation via HTTP GET)
		//$invocationUrl = $masterpaymentServer . '/en/payment/gateway';
		$invocationUrl = $masterpaymentServer;

		
		/*Generate Url String for iFrame*/
		$iFrameString = $invocationUrl;
		foreach ($params as $paramName => $paramValue)
		{
			$paramValue = urlencode($paramValue);
			if( $paramName == "merchantName")
				$iFrameString .= "?".$paramName."=".$paramValue;
			else
				$iFrameString .= "&".$paramName."=".$paramValue;
		}
		$data['forFrame'] = $iFrameString;
		$data['parameter'] = $params;
		return $data;
	}
	
}




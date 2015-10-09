<?php

require_once 'SampleApplicationData.php';
require_once dirname(dirname(__DIR__)) . '/WalletSDK/MasterPassService.php';

class SampleApplicationController {

	
	public $service;
	public $appData;
	
	// constant tax and shipping values for the checkout flow
	const TAX = 3.48;
	const SHIPPING = 8.95;
	
	const SHOPPING_CART_XML = "resources/shoppingCart.xml";
	const MERCHANT_INIT_XML = "resources/merchantInit.xml";
	const MERCHANT_TRANSACTION_XML = "resources/merchantTransaction.xml";
	
	
	/**
	 * Constructor for SampleApplicationController
	 * @param SampleApplicationData $sampleApplicationData
	 */
	public function __construct($sampleApplicationData)
	{
		$consumerKey = $sampleApplicationData->consumerKey;
		$privateKey = $this->getPrivateKey($sampleApplicationData);
		$originUrl = $sampleApplicationData->callbackDomain;
		$this->service = new MasterPassService($consumerKey, $privateKey, $originUrl);
		$this->appData = $sampleApplicationData;
	}
	
	
	
	/**
	 * Index page only
	 * 
	 * Method to parse the shipping profiles from the config files for 
	 * 
	 * @return Array of config file names
	 */
	public static function getShippingProfiles() {		
		
		$handle = dirname(__DIR__) . "/" . SampleApplicationData::RESOURCES_PATH.SampleApplicationData::PROFILE_PATH;

		$configs = scandir($handle);
		
		sort($configs);
		
		$size = count($configs);
		
		for($i = 0; $i < $size; $i++)
		{
			// Using only the unhidden files
			if(substr($configs[$i],0,1) != SampleApplicationData::PERIOD && substr($configs[$i], -4) == SampleApplicationData::CONFIG_SUFFIX){
				$configs[$i] = substr($configs[$i], 0, -4);
			}
			else {
			// Removing any file that starts with a '.' or does not end in '.ini'
				unset($configs[$i]);
			}
		}
		
		sort($configs);
		return $configs;
	}
	
	

	/**
	 * Method to retrieve the private key from the p12 file
	 *
	 * @return Private key string
	 */
	private function getPrivateKey($sampleApplicationData)
	{
		$thispath = dirname(__DIR__) . "/" . $sampleApplicationData->keystorePath;
		error_log("THIS PATH IS " . $thispath);
		$path = realpath($thispath);
		$keystore = array();
		error_log("PATH IS " . $path);
		$pkcs12 = file_get_contents($path);
		trim(openssl_pkcs12_read( $pkcs12, $keystore, $sampleApplicationData->keystorePassword));
		error_log("GOT TO HERE - " . $keystore['pkey']);
		return  $keystore['pkey'];
	}	
	
	/**
	 *  Method to parse and set POST data sent from the index page
	 *  
	 *  @param POST object
	 *  
	 *  @return String : string to append to a URL 
	 */
	public function parsePostData($_POST_DATA){
	
		if($_POST_DATA != null){
			$acceptedCardsString = "";
			
			if(isset($_POST_DATA['acceptedCardsCheckbox'])){
				foreach($_POST_DATA['acceptedCardsCheckbox'] as $value){
					$acceptedCardsString .= $value . ",";
				}
			}
			
			if(isset($_POST_DATA['privateLabelText'])){
				$acceptedCardsString = $acceptedCardsString.$_POST_DATA['privateLabelText'];
			} else {
				$acceptedCardsString = substr($acceptedCardsString,0,strlen($acceptedCardsString)-1);
			}
			
			$this->appData->acceptableCards = $acceptedCardsString;
			$this->appData->xmlVersion = isset($_POST_DATA['xmlVersionDropdown']) ?  $_POST_DATA['xmlVersionDropdown'] : "";
			$this->appData->shippingSuppression = isset($_POST_DATA['shippingSuppressionDropdown']) ? $_POST_DATA['shippingSuppressionDropdown'] : "";
			$this->appData->rewardsProgram = isset($_POST_DATA['rewardsDropdown']) ?  $_POST_DATA['rewardsDropdown'] : "";
				
			if(isset($_POST_DATA['authenticationCheckBox']) && $_POST_DATA['authenticationCheckBox'] == "on") {
				$this->appData->authLevelBasic = true;
			}
			else {
				$this->appData->authLevelBasic = false;
			}
		
			$redirectParameters =  MasterPassService::ACCEPTABLE_CARDS.Connector::EQUALS.$this->appData->acceptableCards
			.Connector::AMP.MasterPassService::VERSION.Connector::EQUALS.$this->appData->xmlVersion
			.Connector::AMP.MasterPassService::SUPPRESS_SHIPPING_ADDRESS.Connector::EQUALS.$this->appData->shippingSuppression
			.Connector::AMP.MasterPassService::AUTH_LEVEL.Connector::EQUALS.($this->appData->authLevelBasic?"true":"false")
			.Connector::AMP.MasterPassService::ACCEPT_REWARDS_PROGRAM.Connector::EQUALS.$this->appData->rewardsProgram;
			return $redirectParameters;
		}
	}	
	
	public function processParameters($_POST_DATA) {
		if($_POST_DATA) {
			$acceptedCardsString = "";
			
			if(isset($_POST_DATA['acceptedCardsCheckbox'])){
				foreach($_POST_DATA['acceptedCardsCheckbox'] as $value){
					$acceptedCardsString .= $value . ",";
				}
			}
			
			if(isset($_POST_DATA['privateLabelText'])){
				$acceptedCardsString = $acceptedCardsString.$_POST_DATA['privateLabelText'];
			} else {
				$acceptedCardsString = substr($acceptedCardsString,0,strlen($acceptedCardsString)-1);
			}
			
			$this->appData->acceptableCards = $acceptedCardsString;
			$this->appData->xmlVersion = isset($_POST_DATA['xmlVersionDropdown']) ?  $_POST_DATA['xmlVersionDropdown'] : "";
			$this->appData->shippingSuppression = isset($_POST_DATA['shippingSuppressionDropdown']) ? $_POST_DATA['shippingSuppressionDropdown'] : "";
			$this->appData->rewardsProgram = isset($_POST_DATA['rewardsDropdown']) ?  $_POST_DATA['rewardsDropdown'] : "";
			$this->appData->shippingProfile = isset($_POST_DATA['$shippingProfileDropdown']) ?  $_POST_DATA['shippingProfileDropdown'] : "";
				
			if(isset($_POST_DATA['authenticationCheckBox']) && $_POST_DATA['authenticationCheckBox'] == "on") {
				$this->appData->authLevelBasic = true;
			}
			else {
				$this->appData->authLevelBasic = false;
			}
		
		}
		return $this->appData;
	}
	
	public function setPostbackParameter($_POST_DATA) {
		if($_POST_DATA) {
			$postbackParameter = '';
			if(isset($_POST["postbackVersionDropdown"])) {
				$postbackParameter = '&postbackVersionDropdown='.$_POST["postbackVersionDropdown"];
			}
			$this->appData->callbackUrl = $this->appData->callbackUrl."?profileName=".$profileName.$postbackParameter;
		}
		return $this->appData;
	}
	
	public function setCallbackParameters($_GET_DATA) {
		$this->appData->requestToken = isset($_GET_DATA[MasterPassService::OAUTH_TOKEN]) ? $_GET_DATA[MasterPassService::OAUTH_TOKEN] : NULL;
		$this->appData->requestVerifier = isset($_GET_DATA[MasterPassService::OAUTH_VERIFIER]) ? $_GET_DATA[MasterPassService::OAUTH_VERIFIER] : NULL;
		$this->appData->checkoutResourceUrl = isset($_GET_DATA[MasterPassService::CHECKOUT_RESOURCE_URL]) ? $_GET_DATA[MasterPassService::CHECKOUT_RESOURCE_URL] : NULL;
		return $this->appData;
	}
	
	public function setPairingDataTypes($dataTypes) {
		$this->appData->pairingDataTypes = $dataTypes;
		return $this->appData;
	}
	
	public function setPrecheckoutCardId($cardId) {
		$this->appData->preCheckoutCardId = $cardId;
		return $this->appData;
	}
	
	public function setPrecheckoutShippingId($shippingId) {
		$this->appData->preCheckoutShippingAddressId = $shippingId;
		return $this->appData;
	}
	
	public function setPairingToken($pairingToken) {
		if($pairingToken != NULL) {
			$this->appData->pairingToken = $pairingToken;
		}
		return $this->appData;
	}
	
	public function setPairingVerifier($pairingVerifier) {
		if($pairingVerifier != NULL) {
			$this->appData->pairingVerifier = $pairingVerifier;
		}
		return $this->appData;
	}
	
	/**
	* This method parses the shoppingcart.xml file use to populate shopping cart items in the the checkout flow
	*
	* *only handles USD currency
	*
	* Returns a XML class of the shopping cart data
	*
	* @param String : $callbackdoamin
	*/
	public function parseShoppingCartXMLPrint() {
	
		$shoppingCartData =  $this->parseShoppingCartXML("");
		$shoppingCartData->ShoppingCart->Subtotal = (double)$shoppingCartData->ShoppingCart->Subtotal/100;
	
		foreach($shoppingCartData->ShoppingCart->ShoppingCartItem as $item){
			$item->Value = (double)$item->Value/100;
		}

		return $shoppingCartData;
	}
	
	/**
	 * Parses the shoppingcart.xml file and returns a XML object with the data
	 *
	 * @param String: $requestToken
	 *
	 * @return XML object
	 */
	public function parseShoppingCartXML($requestToken) {
		$shoppingCartData = simplexml_load_file(SampleApplicationController::SHOPPING_CART_XML);
		$shoppingCartData->OAuthToken = $requestToken;
		$shoppingCartData->OriginUrl = $this->appData->originUrl;
		$shoppingCartData = $this->updateImageURL($shoppingCartData);
		return $shoppingCartData;
	}
	
	
	public function parseMerchantInitXML($pairingToken) {
		$merchantInitData = simplexml_load_file(SampleApplicationController::MERCHANT_INIT_XML);
		$merchantInitData->OAuthToken = $pairingToken;
		$merchantInitData->OriginUrl = $this->appData->originUrl;
		return $merchantInitData;
	}

	/**
	 * Update the domain of the Image URL's to the callback domain listed in the config.ini file.
	 *
	 * @param $shoppingCartData
	 * @param $callbackdomain
	 *
	 * @return XML object
	 */
	private function updateImageURL($shoppingCartData){
		$break = explode('/', $_SERVER['REQUEST_URI']);
	
		foreach($shoppingCartData->ShoppingCart->ShoppingCartItem as $item) {
			$item->ImageURL = str_ireplace("http://projectabc.com", $this->appData->callbackDomain."/".$break[1]."/".$break[2], $item->ImageURL);
		}
	
		return $shoppingCartData;
	}
	
	/**
	 * Method to post the Merchant Initialization XML to MasterCards services. The XML is parsed from the shoppingCart.xml file.
	 *
	 * @param data
	 *
	 * @return Command bean with the Shopping Cart response set.
	 *
	 * @throws Exception
	 */
	public function postMerchantInit($pairingToken)  {
	
		$merchantInitRequest = $this->parseMerchantInitXML($pairingToken);
		error_log("postMerchantInit - " . $merchantInitRequest->asXML());
		$merchantInitResponse = $this->service->postMerchantInitData($this->appData->merchantInitUrl, $merchantInitRequest->asXML());
		return $merchantInitResponse;
	}	
	
	public function parsePrecheckoutXml() {
		$typesXml = "";
		foreach ($this->appData->pairingDataTypes as $dataType) {
			$typesXml = $typesXml . sprintf("<PairingDataType><Type>%s</Type></PairingDataType>", $dataType);
		}
		$preCheckoutRequest = simplexml_load_string(sprintf("<PrecheckoutDataRequest><PairingDataTypes>%s</PairingDataTypes></PrecheckoutDataRequest>", 
				$typesXml));
		
		return $preCheckoutRequest->asXML();
	}

	public function getRequestToken() {
		$requestTokenResponse = $this->service->getRequestToken($this->appData->requestUrl, $this->appData->callbackUrl);
		$this->appData->requestTokenResponse = $requestTokenResponse;
		$this->appData->requestToken = $requestTokenResponse->requestToken;
		return $this->appData;
	}
	
	public function getPairingToken() {
		$pairingTokenResponse = $this->service->getRequestToken($this->appData->requestUrl, $this->appData->callbackUrl);
		$this->appData->pairingTokenResponse = $pairingTokenResponse;
		$this->appData->pairingToken = $pairingTokenResponse->requestToken;
		return $this->appData;
	}
	
	public function getLongAccessToken() {
		$longAccessTokenResponse = $this->service->GetAccessToken($this->appData->accessUrl, $this->appData->pairingToken, $this->appData->pairingVerifier);
		$this->appData->longAccessTokenResponse = $longAccessTokenResponse;
		$this->appData->longAccessToken = is_null($longAccessTokenResponse) ? "" : $longAccessTokenResponse->accessToken;
		$this->appData->oAuthSecret = is_null($longAccessTokenResponse) ? "" : $longAccessTokenResponse->oAuthSecret;
		return $this->appData;
	}
	
	public function getAccessToken() {
		$accessTokenResponse = $this->service->GetAccessToken($this->appData->accessUrl,$this->appData->requestToken, $this->appData->requestVerifier);
		$this->appData->accessTokenResponse = $accessTokenResponse;
		$this->appData->accessToken = $accessTokenResponse->accessToken;
		return $this->appData;
	}
	
	public function postShoppingCart() {
		$shoppingCartRequest = $this->parseShoppingCartXML($this->appData->requestToken);
		$this->appData->shoppingCartRequest = $shoppingCartRequest->asXML();
		$this->appData->shoppingCartResponse = $this->service->postShoppingCartData($this->appData->shoppingCartUrl, $this->appData->shoppingCartRequest);
		error_log($this->appData->shoppingCartResponse);
		return $this->appData;
	}
	
	public function postMerchantInitData() {
		$merchantInitRequest = $this->parseMerchantInitXML($this->appData->pairingToken);
		$this->appData->merchantInitRequest = $merchantInitRequest->asXML();
		$this->appData->merchantInitResponse = $this->service->postMerchantInitData($this->appData->merchantInitUrl, $this->appData->merchantInitRequest);
		return $this->appData;
	}
	
	public function postPreCheckoutData($longAccessToken) {
		$this->appData->preCheckoutRequest = $this->parsePrecheckoutXml();
		$this->appData->preCheckoutResponse = $this->service->getPreCheckoutData($this->appData->preCheckoutUrl, $this->appData->preCheckoutRequest, $longAccessToken);

		error_log("Pre PreCheckout Response ********");
		error_log($this->appData->preCheckoutCardId);
		error_log($this->appData->preCheckoutShippingAddressId);
		error_log($this->appData->preCheckoutWalletId);
		error_log($this->appData->longAccessToken);
		error_log($this->appData->preCheckoutTransactionId);
		error_log($this->appData->walletName);
		error_log($this->appData->consumerWalletId);
		
		// Special syntax for working with SimpleXMLElement objects
		$preCheckoutResponse = simplexml_load_string($this->appData->preCheckoutResponse);
		if ($preCheckoutResponse != null) {
// 			$cardIdArray = $preCheckoutResponse->xpath("/PrecheckoutDataResponse/PrecheckoutData/Cards/Card/CardId");
// 			if ($cardIdArray != null) {
// 				$this->appData->preCheckoutCardId = (string) $cardIdArray[0];
// 			}
		
// 			$addressIdArray = $preCheckoutResponse->xpath("/PrecheckoutDataResponse/PrecheckoutData/ShippingAddresses/ShippingAddress/AddressId");
// 			if ($addressIdArray != null) {
// 				$this->appData->preCheckoutShippingAddressId = (string) $addressIdArray[0];
// 			}
		
// 			$preCheckoutWalletIdArray = $preCheckoutResponse->xpath("/PrecheckoutDataResponse/PrecheckoutData/WalletId");
// 			if($preCheckoutWalletIdArray != null) {
// 				$this->appData->preCheckoutWalletId = (string) $preCheckoutWalletIdArray[0];
// 			}
		
// 			$longAccessTokenArray = $preCheckoutResponse->xpath("/PrecheckoutDataResponse/AccessToken");
// 			if($longAccessTokenArray != null) {
// 				$this->appData->longAccessToken = (string) $longAccessTokenArray[0];
// 			}
		
// 			$preCheckoutTransactionIdArray = $preCheckoutResponse->xpath("/PrecheckoutDataResponse/PrecheckoutData/PrecheckoutTransactionId");
// 			if($preCheckoutTransactionIdArray != null) {
// 				$this->appData->preCheckoutTransactionId = (string) $preCheckoutTransactionIdArray[0];
// 			}
			
			$this->appData->preCheckoutCardId = (string) $preCheckoutResponse->PrecheckoutData->Cards->Card->CardId;
			$this->appData->preCheckoutShippingAddressId = (string) $preCheckoutResponse->PrecheckoutData->ShippingAddresses->ShippingAddress->AddressId;
			$this->appData->preCheckoutWalletId = (string) $preCheckoutResponse->PrecheckoutData->WalletId;
			$this->appData->longAccessToken = (string) $preCheckoutResponse->LongAccessToken;
			$this->appData->preCheckoutTransactionId = (string) $preCheckoutResponse->PrecheckoutData->PrecheckoutTransactionId;
			$this->appData->walletName = (string) $preCheckoutResponse->PrecheckoutData->WalletName;
			$this->appData->consumerWalletId = (string) $preCheckoutResponse->PrecheckoutData->ConsumerWalletId;
			
			error_log("Post PreCheckout Response ********");
			error_log($this->appData->preCheckoutCardId);
			error_log($this->appData->preCheckoutShippingAddressId);
			error_log($this->appData->preCheckoutWalletId);
			error_log($this->appData->longAccessToken);
			error_log($this->appData->preCheckoutTransactionId);
			error_log($this->appData->walletName);
			error_log($this->appData->consumerWalletId);
		}

		return $this->appData;
	}
	
	public function getCheckoutData() {
		$this->appData->checkoutData = $this->service->GetPaymentShippingResource($this->appData->checkoutResourceUrl,$this->appData->accessToken);
		$checkoutObject = SampleApplicationHelper::formatResource($this->appData->checkoutData);
		$this->appData->transactionId = (string) $checkoutObject->TransactionId;
		return $this->appData;
	}
	
	public function logTransaction() {
		$shoppingCartData =  $this->parseShoppingCartXML("");
		
		$merchantTransaction = simplexml_load_file(SampleApplicationController::MERCHANT_TRANSACTION_XML);
		$merchantTransaction->MerchantTransactions->TransactionId = $this->appData->transactionId;
		$merchantTransaction->MerchantTransactions->ConsumerKey = $this->service->getConsumerKey();
		$merchantTransaction->MerchantTransactions->Currency = 'USD';
		$merchantTransaction->MerchantTransactions->OrderAmount = $shoppingCartData->ShoppingCart->Subtotal + $this->appData->tax + $this->appData->shipping;
		$merchantTransaction->MerchantTransactions->PurchaseDate = date(DATE_ATOM);
		$merchantTransaction->MerchantTransactions->TransactionStatus = TransactionStatus::Success;
		$merchantTransaction->MerchantTransactions->ApprovalCode = MasterPassService::APPROVAL_CODE;
		$this->appData->postTransactionRequest = $merchantTransaction->asXML();
		
		$this->appData->postTransactionResponse = $this->service->PostCheckoutTransaction($this->appData->postbackUrl, $this->appData->postTransactionRequest);
		
		return $this->appData;
	}
}



?>



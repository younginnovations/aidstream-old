<?php
class Model_Twitter {
	// Twitter API Configuration
	public function __construct() {
		// Config path
		$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
		$twitterOauth = $config->service->twitter->oauth;
		$accessToken = new Zend_Oauth_Token_Access();
		$accessToken->setToken($twitterOauth->oauthToken)
		            ->setTokenSecret($twitterOauth->oauthTokenSecret);

		$this->options = array(
		    'username' => $twitterOauth->username,
		    'accessToken' => $accessToken,
		    'oauthOptions' => array(
		        'consumerKey' => $twitterOauth->consumerKey,
		        'consumerSecret' => $twitterOauth->consumerSecret
		    )
		);
	}

	// Verify Credentials
	private function verifyCredentials() {
		$twitter = new Zend_Service_Twitter($this->options);
		$response = $twitter->account->verifyCredentials();

		if ( !$response || !empty($response->error) ) {
		   return false;
		} else {
			return $twitter;
		}
	}

	public function sendTweet() {
		$identity = Zend_Auth::getInstance()->getIdentity();
		$accountId = $identity->account_id;

		$regInfoModel = new Model_RegistryInfo();
		$regInfo = $regInfoModel->getOrgRegistryInfo($accountId);
		$registryUrl = "/publisher/".$regInfo->publisher_id;
		
		$model = new User_Model_DbTable_Account();
		$row = $model->getAccountRowById($accountId);
		// If twitter screen name is present
		$twitter = $this->verifyCredentials();
		if (is_object($twitter)) {
			if (strlen($row['twitter']) != 0) {
				$status = $row['name'] . ' ' . $row['twitter'] . ' has published their #IATIData. View the 
							data here: http://iatiregistry.org' . $registryUrl . ' #AidStream';
			} else {
				$status = $row['name'] . ' has published their #IATIData. View the 
							data here: http://iatiregistry.org' . $registryUrl . ' #AidStream';
			}
			$twitter->statuses->update($status);
		} else {
			return false;
		}
	}

	public function checkUsername($username) {
		$twitter = $this->verifyCredentials();	
		if (is_object($twitter)) {
			try {
		    	$response = $twitter->usersShow($username);
		    	$response = $response->toValue();
		    	if (isset($response->id)) {
		    		return 'VALID';
		    	} else {
		    		return 'INVALID';
		    	}
		    } catch (Exception $e) {
		    	return 'INVALID';
		    }
		} else {
			return 'ERROR';
		}
	}

}
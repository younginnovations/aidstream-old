<?php
class Model_Twitter {

	// Twitter API Configuration and Verification
	private function verifyCredentials() {
		$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
		$twitterOauth = $config->service->twitter->oauth;
		$accessToken = new Zend_Oauth_Token_Access();
		$accessToken->setToken($twitterOauth->oauthToken)
		            ->setTokenSecret($twitterOauth->oauthTokenSecret);

		$options = array(
		    'username' => $twitterOauth->username,
		    'accessToken' => $accessToken,
		    'oauthOptions' => array(
		        'consumerKey' => $twitterOauth->consumerKey,
		        'consumerSecret' => $twitterOauth->consumerSecret
		    )
		);

		$twitter = new Zend_Service_Twitter($options);
		$response = $twitter->account->verifyCredentials();

		if ( !$response || !empty( $response->error ) ) {
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
		if (strlen($row['twitter']) != 0)
			$twitter = $this->verifyCredentials();
			if (is_object($twitter)) {
				$twitter->statuses->update($row['name'] . ' (' . $row['twitter'] .  ') has published their aid-data. View the data here: http://iatiregistry.org' . $registryUrl . ' #AidStream #IATI');
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
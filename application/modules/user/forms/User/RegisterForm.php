<?php

class User_Form_User_RegisterForm extends App_Form
{

    public function init()
    {
        $this->setName('Register');
        $this->setMethod('post');
	
	$userInfo = new Iati_Form_Element_Note('user_info_message');
	$userInfo->setValue("Please enter your personal details.")
	    ->setAttrib('class' , 'form-message');
        
        $orgname = new Zend_Form_Element_Text('org_name');
        $orgname->setLabel('Organisation Name')
            ->setRequired()
            ->setAttrib('class', 'form-text');
            
        $orgaddress = new Zend_Form_Element_Textarea('org_address');
        $orgaddress->setLabel('Organisation Address')
            ->setAttrib('rows' , 4)
            ->setRequired()
            ->setAttrib('class', 'form-text');

        $account_identifier = new Zend_Form_Element_Text('account_identifier');
        $account_identifier->setLabel('Organisation User Identifier')
            ->setRequired()
            ->addValidator('Db_NoRecordExists', false, array('table' => 'account','field' => 'username'))
            ->addErrorMessage('This Organisation User Identifier is already used.')
            ->setDescription("Your organisation user identifier will be used as a prefix for all the 
                              AidStream users in your organisation. We recommend that you use a short 
                              abbreviation that uniquely identifies your organisation. If your organisation 
                              is 'Acme Bellus Foundation', your organisation user identifier should be 
                              'abf', depending upon it's availability.")
            ->setAttrib('class', 'form-text');
            
        $firstname = new Zend_Form_Element_Text('first_name');
        $firstname->setLabel('Your First Name')
            ->setRequired()
            ->setAttrib('class', 'form-text');
            
        $lastname = new Zend_Form_Element_Text('last_name');
        $lastname->setLabel('Your Last Name')
            ->setRequired()
            ->setAttrib('class', 'form-text');
            
        $username = new Zend_Form_Element_Text('user_name');
        $username->setLabel('User Name')
            ->setRequired()
            ->setAttrib('readonly' , true)
            ->setDescription("AidStream will create a default username with your Organisation User 
                              Identifier as prefix. You will not be able to change '_admin' part of the 
                              username. This user will have administrative privilege and can create 
                              multiple AidStream users with different set of permissions.")
            ->setAttrib('class', 'form-text');


        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Your Email')->setRequired()->addValidator('emailAddress', false)
            ->addValidator('Db_NoRecordExists', false, array('table' => 'user',
                'field' => 'email'))
            ->setAttrib('class', 'form-text');


        $passwordConfirmation = new App_Validate_PasswordConfirmation();

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
            ->setRequired()
            ->setAttrib('class', 'form-text')
            ->addValidator($passwordConfirmation);


        $confirmPassword = new Zend_Form_Element_Password('confirmpassword');
        $confirmPassword->setLabel('Confirm Password')
            ->setRequired()
            ->setAttrib('class', 'form-text')
            ->addValidator($passwordConfirmation);

        $publisherId = new Zend_Form_Element_Text('publisher_id');
        $publisherId->setLabel('Publisher Id')
            ->setAttrib('class', 'form-text')
            ->addErrorMessage('Please Enter the Publisher ID');
        
        $apiKey = new Zend_Form_Element_Text('api_key');
        $apiKey->setLabel('API Key')
            ->setAttrib('class', 'form-text')
            ->addErrorMessage('Please Enter an API key');
            
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        /*
        $captcha = new Zend_Form_Element_Captcha(
            'captcha', 
            array(
                    'label' => 'Please enter the characters shown in the picture',
                    'captcha' => array(
                                       'captcha' => 'Image',
                                       'wordLen' => 6,
                                       'timeout' => 300,
                                       'font' => APPLICATION_PATH.'/../public/font/Ubuntu-B.ttf',
                                       'imgDir' => APPLICATION_PATH.'/../public/captcha/',
                                       'imgUrl' => $baseUrl.'/captcha/',
                                       'dotNoiseLevel' => 70,
                                       'lineNoiseLevel' => 5
                                    )
                    )
            );
        */
        $publickey = '6Ld6RM0SAAAAANYtQD4j-0THK1HBXLUhAsQCXyiH';
        $privatekey = '6Ld6RM0SAAAAAJlk5mZV9tZ65xfrmHEoXtmYdyHz';
        $recaptcha = new Zend_Service_ReCaptcha($publickey, $privatekey);

        $captcha = new Zend_Form_Element_Captcha('captcha',
            array(
		'label'	 => "Please enter the text in the box",
                'captcha'       => 'ReCaptcha',
                'captchaOptions' => array('captcha' => 'ReCaptcha', 'service' => $recaptcha),
                'ignore' => true
                )
        );

            

        $this->addElements(
	    array(
		$userInfo , $orgname, $orgaddress, $account_identifier ,
		$firstname, $lastname, $userIdentifier, $username, $email,
		$password, $confirmPassword , $captcha
	    )
	);
        
        $this->addDisplayGroup(
	    array('org_name' , 'org_address' , 'account_identifier'),
	    'organisation_info',
	    array('legend' => 'Organisation Info')
	);
        
        $this->addDisplayGroup(
	    array('user_info_message', 'first_name' , 'last_name' ,
		  'email', 'user_identifier', 'user_name' , 'password',
		  'confirmpassword' , 'captcha'),
	    'user_info',
	    array('legend' => 'User Info')
	);
        /*
        $this->addDisplayGroup(
                                array('publisher_id' , 'api_key'),
                                'register_registry_info',
                                array(
                                      'legend' => 'Registry Info',
                                      )
                            );
        $reg = $this->getDisplayGroup('register_registry_info');
        $reg->setDescription("If you don't have the registry info please visit the <a href='http://www.iatiregistry.org' target='_blank'>Iati Registry</a>")
            ->addDecorators(array(
                array('Description', array('escape' => false, 'tag' => 'div')),
              ));
        */
        // Add wrapper to all element
        foreach($this->getElements() as $item)
        {
	    if(($item->getType()) == 'Iati_Form_Element_Note') continue;
	    
            $item->addDecorators( array(
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                    )
            );
        }
        $groups = $this->getDisplayGroups();
        foreach($this->getDisplayGroups() as $group){
            $group->addDecorators(array(
                array(
                      array( 'wrapperAll' => 'HtmlTag' ),
                      array( 'tag' => 'div','class'=>'default-activity-list'))
            ));
        }
        $create = new Zend_Form_Element_Submit('create_new_account');
        $create->setLabel('Sign Up');
        $this->addElement($create);
    }

}
<?php

class User_Form_User_Edit extends App_Form
{

    public function init()
    {
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $auth = Zend_Auth::getInstance()->getIdentity();
        $user_id = $auth->user_id;
        $roleName = $auth->role;
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $clause = $db->quoteInto('user_id != ?', $user_id);
        $accountObj = new User_Model_DbTable_Account();
        $userName = strtok($auth->user_name, '_');
        $account = $accountObj->getAccountRowByUserName('account', 'username', $userName);
        $usernameClause = $db->quoteInto('username != ?', $userName);
        $this->setName('Edit Account');
        $form = array();

        $form['first_name'] = new Zend_Form_Element_Text('first_name');
        $form['first_name']->setLabel('First Name')
            ->setRequired()
            ->setAttrib('class', 'form-text');
        $form['last_name'] = new Zend_Form_Element_Text('last_name');
        $form['last_name']->setLabel('Last Name')
           ->setRequired()
           ->setAttrib('class', 'form-text');   
        $form['email'] = new Zend_Form_Element_Text('email');
        $form['email']->setLabel('Email')
           ->setRequired()
           ->addValidator('emailAddress', false)
           ->setAttrib('class', 'form-text')
           ->addValidator('Db_NoRecordExists', false,
                 array('table' => 'user', 'field' => 'email', 'exclude' => $clause,
                 'messages' => array(
                 Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => 'Email Address already exists.')));
        
        if($roleName != 'superadmin')
        {
            $form['name'] = new Zend_Form_Element_Text('name');
            $form['name']->setLabel('Organisation Name')
                ->setRequired()
                ->setAttrib('class', 'form-text');

            $form['address'] = new Zend_Form_Element_Textarea('address');
            $form['address']->setLabel('Organisation Address')
                ->setRequired()
                ->setAttrib('rows', '4')
                ->setAttrib('class', 'form-text');

            $form['url'] = new Zend_Form_Element_Text('url');
            $form['url']->setLabel('Organisation Url')
                ->addValidator(new App_Validate_Url())
                ->setAttrib('class', 'form-text');
            
            $form['telephone'] = new Zend_Form_Element_Text('telephone');
            $form['telephone']->setLabel('Organisaton Telephone')
                ->addValidator(regex, false, array(
                                            'pattern' => '/^[\d -]+$/',
                                            'messages' => 'Invalid telephone number.'
                                        )
                            )
                ->setAttrib('class', 'form-text');
                 
        }
        
        if($roleName == 'admin')
        {
            $twitterUsernameValidator = new App_Validate_TwitterUsername();
            $form['twitter'] = new Zend_Form_Element_Text('twitter');
            $form['twitter']->setLabel('Organisaton Twitter')
                ->setAttrib('class', 'form-text')
                ->setDescription("Please insert a valid twitter username. Example: '@oxfam' or 'oxfam'")
                ->addValidator($twitterUsernameValidator)
                ->addValidator('Db_NoRecordExists', false,
                      array('table' => 'account', 'field' => 'twitter', 'exclude' => $usernameClause,
                      'messages' => array(
                      Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => 'Twitter handle already in use.')));

            $filePath = $baseUrl.'/uploads/image/'.$account['file_name'] ;
            $remove = $baseUrl.'/user/user/remove/user_id/';
            if($account['file_name']){
                $form['image'] = new Zend_Form_Element_Image('image');
                $form['image']->setImage($filePath)
                    ->setLabel('Organisation Logo')
                    ->setDescription('<a href="'.$remove.$user_id.'/user_name/'.$userName.
                                     '" class ="remove-logo" title = "Remove Logo" >Remove</a>')
                    ->setDecorators(array(
                                    'ViewHelper',
                                    array('Description', array('escape' => false, 'tag' => false)),
                                    array('HtmlTag', array('tag' => 'dd')),
                                    array('Label', array('tag' => 'dt')),
                                    'Errors',
                                   ));

            }
            $form['file'] = new Zend_Form_Element_File('file');
            $form['file']->setLabel('Change')
                ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
                ->setDescription('Please use jpg/jpeg/png/gif format and 150x150 dimensions image.')
                ->getValidator('Extension')->setMessage('Please use jpg/jpeg/png/gif format image.');
            if(!$account['file_name']){
                $form['file']->setLabel('Upload Logo');
            }
            $form['disqus_comments'] = new Zend_Form_Element_Checkbox('disqus_comments');
            $form['disqus_comments']->setLabel('Disqus Comments')
                ->setDescription('Enable/disable comments on your <a href="/organisation?reporting_org=' . rawurlencode($account->name) .'" target="_blank"> organization page</a>.');
            $form['disqus_comments']->getDecorator('Description')->setOption('escape', false);
        }

        if($roleName == 'user')
        {
            $form['address']->setAttrib('readonly', 'true');
            $form['name']->setAttrib('readonly', 'true');
        }
        
        foreach($form as $element){
            $element->addDecorators( array(
                        array(
                            array( 'wrapperAll' => 'HtmlTag' ),
                            array( 'tag' => 'div','class'=>'clearfix form-item')
                        )
                    )
            );
        }

        $this->addElements($form);
        $this->addDisplayGroup(
                               array_keys($form),
                               'edit-user-form',
                               array('legend'=> 'Edit Profile')
                            );
        $editUser = $this->getDisplayGroup('edit-user-form');
        $editUser->addDecorators(array(
                array(
                      array( 'wrapperAll' => 'HtmlTag' ),
                      array( 'tag' => 'div','class'=>'default-activity-list'))
            ));
        $save = new Zend_Form_Element_Submit('Save');
        $save->setValue('save')
            ->setAttrib('class' , 'form-submit');
        $this->addElement($save);
    }
}
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

        $this->setName('Edit Account');
        $form = array();

        if($roleName != 'superadmin')
        {
        $form['name'] = new Zend_Form_Element_Text('name');
        $form['name']->setLabel('Orgainisation Name')
            ->setAttrib('class', 'form-text')
            ->setAttrib('readonly', 'true')
            ->setRequired();
        $form['address'] = new Zend_Form_Element_Textarea('address');
        $form['address']->setLabel('Orgainisation Address')
            ->setRequired()
            ->setAttrib('rows', '4')
            ->setAttrib('class', 'form-text');
        }
        if($roleName == 'user')
        {
            $form['address']->setAttrib('readonly', 'true');
        }
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

        if($roleName == 'admin')
        {
            $filePath = $baseUrl.'/uploads/image/'.$account['file_name'] ;
            $remove = $baseUrl.'/user/user/remove/user_id/';

            if($account['file_name']){
                $form['image'] = new Zend_Form_Element_Image('image');
                $form['image']->setImage($filePath)
                    ->setLabel('Logo')
                    ->setDescription('<a href="'.$remove.$user_id.'/user_name/'.$userName.'" class ="remove-logo" title = "Remove Logo" >Remove</a>')
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

            $form['url'] = new Zend_Form_Element_Text('url');
            $form['url']->setLabel('Organisation Url')
            ->addValidator(new App_Validate_Url())
            ->setAttrib('class', 'form-text');
        }
        foreach($form as $element){
            $element->addDecorators( array(
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
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
        $save = new Zend_Form_Element_Submit('save');
        $save->setValue('save')
            ->setAttrib('class' , 'form-submit');
        $this->addElement($save);
    }
}
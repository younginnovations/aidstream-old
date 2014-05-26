<?php

class User_Form_User_Changeusername extends App_Form
{

    public function init()
    {
        $form = array();

        $this->setName('changeusername');

        $auth = Zend_Auth::getInstance()->getIdentity();
        
        // For Admin
        if ($auth->role == 'admin'):
            $account_identifier = new Zend_Form_Element_Text('account_identifier');
            $account_identifier->setLabel('Account Identifier')
                ->setRequired()
                ->addValidator('Db_NoRecordExists', false, array('table' => 'account','field' => 'username'))
                ->addErrorMessage('This account identifier is already used')
                ->setDescription("Your account identifier will be used as a prefix for all the users in your organisation. We recommend that you use a short abbreviation that uniquely identifies your organisation. If your account identifier is 'abc' the administrator will be 'abc_admin' and the other users be 'abc'_name.")
                ->setAttrib('class', 'form-text');

            $user_name = new Zend_Form_Element_Text('user_name');
            $user_name->setLabel('New Username')
                ->setRequired()
                ->setAttrib('class', 'form-text')
                ->setValue('_admin')
                ->setAttrib('readonly' , true)
                ->addValidator('Db_NoRecordExists', false, array('table' => 'account','field' => 'username'))
                ->addErrorMessage('This account identifier is already used');


            $submit = new Zend_Form_Element_Submit('Submit');
            $submit->setValue('change')
                ->setAttrib('class', 'form-submit')
                ->setAttrib('id', 'change-username');
        endif;

        // For Groupadmin
        if ($auth->role == 'groupadmin'):
            $group_identifier = new Zend_Form_Element_Text('group_identifier');
            $group_identifier->setLabel('Group Identifier')
                ->setRequired()
                ->addValidator('Db_NoRecordExists', false, array('table' => 'user_group','field' => 'username'))
                ->addErrorMessage('This group identifier is already used')
                ->setDescription("Your group identifier will be used as a prefix for your username. 
                    We recommend that you use a short abbreviation that uniquely identifies your organisation 
                    group. If your group identifier is 'abc' the username will be 'abc_group'.")
                ->setAttrib('class', 'form-text');

            $user_name = new Zend_Form_Element_Text('user_name');
            $user_name->setLabel('New Username')
                ->setRequired()
                ->setAttrib('class', 'form-text')
                ->setValue('_group')
                ->setAttrib('readonly' , true);

            $submit = new Zend_Form_Element_Submit('Submit');
            $submit->setValue('change')
                ->setAttrib('class', 'form-submit')
                ->setAttrib('id', 'change-group-username');
        endif;

        $this->addElements(array($user_name, $account_identifier, $group_identifier));
        $this->addDisplayGroup(
            array('account_identifier', 'group_identifier', 'user_name'),
            'field1',
            array('legend'=>'Change Username')
        );

        $this->addElement($submit);
        $this->setMethod('post');

        $displayGroup = $this->getDisplayGroup('field1');
        $displayGroup->addDecorators(array(
                array(
                      array( 'wrapperAll' => 'HtmlTag' ),
                      array( 'tag' => 'div','class'=>'default-activity-list'))
            ));

        foreach($this->getElements() as $element){
            $element->addDecorators(array(
                array(
                      array( 'wrapperAll' => 'HtmlTag' ),
                      array( 'tag' => 'div','class'=>'form-item'))
            ));
        }
    }
}
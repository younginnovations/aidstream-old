<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->setLayout('layout_wep');
    }

    public function indexAction()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $identity = Zend_Auth::getInstance()->getIdentity();
            if ($identity->role == 'superadmin') {
                $this->_redirect('admin/dashboard');
            } elseif ($identity->role == 'admin') {
                $this->_redirect('wep/dashboard');
            }
            elseif ($identity->role == "user") {
                $this->_redirect('wep/dashboard');
            }
        }

        $form = new User_Form_User_Login();
        $this->view->form = $form;  
    }
}


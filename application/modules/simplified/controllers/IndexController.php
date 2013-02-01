<?php

class Simplified_IndexController extends Zend_Controller_Action
{

    public function init()
    {
	
    }

    public function indexAction()
    {
        $this->_redirect('simplified/default/dashboard');
    }
}


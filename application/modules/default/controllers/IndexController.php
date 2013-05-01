<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
	$this->_helper->layout->setLayout('layout_wep_index');
    }
    /**
     * Home page
     */
    public function indexAction()
    {
	$model = new User_Model_DbTable_Account();
	$count = $model->getAccountCount();
	
	$this->view->orgCount = $count['total'];
    }
    
    /**
     * About us page.
     */
    public function aboutAction(){}
    
    /**
     * Users list page
     */
    public function usersAction()
    {
	    $model = new User_Model_DbTable_Account();
	    /* Moved to a new page from footer as per new home page*/
	    $images = $model->getFileNamesForFooter();
	    $count = $model->getAccountCount();
	    
	    $this->view->images = $images;
	    $this->view->count = $count['total'];
    }
}


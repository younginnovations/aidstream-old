<?php 

class Zend_View_Helper_BaseUrl
{
	  function baseUrl() {
	  	  $fc = Zend_Controller_Front::getInstance();
	  	  
	  	  return $fc->getBaseUrl();
	  }
	  /**
	   * base URL helper, common, flash etc helpers are on both the module helper folder
	   * @todo remove the unused helper - Geshan (24-Nov-2011)
	   */
}
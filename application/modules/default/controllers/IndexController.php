<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->_redirect('user/user/logout');
        
       /* $xml = simplexml_load_file('/home/bibek/src/htdocs/yipl/webservice-iatixml/tests/xml/iati-activities-sample-bad.xml');
        $domDocument = dom_import_simplexml($xml)->ownerDocument;
        
        $domElement = new DOMDocument();
        $domElement->load('/home/bibek/src/htdocs/yipl/webservice-iatixml/tests/xml/iati-activities-sample.xml');
        
        $iatiValidator = new Iati_Iatischema_Validator();
        $result = $iatiValidator->validate($domDocument);

        print_r($result);*/
        
        
//        $this->_redirect('code-list/code-list-index/langid/1');
        
    }
}


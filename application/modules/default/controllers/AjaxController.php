<?php
/**
 * Controller to handle all the ajax requests like add form, remove form.
 * @author YIPL dev team
 */
class AjaxController extends Zend_Controller_Action
{ 
    public function preDispatch()
    {
        $this->_helper->layout()->setLayout('layout_wep');
        //Using Ajax for element to load in view-activity page
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('element', 'html')->initContext('html'); 
    }
    
    public function getFormAction()
    {
        $elementClass = $this->_getParam('classname');
        $refItem = $this->_getParam('refele');

        $elementName =  "Iati_Aidstream_Element_".$elementClass;
        $element = new $elementName();

        preg_match( "/{$element->getClassName()}-\d+/" , $refItem ,  $matches );
        $ele = $matches[0];
        $count = substr($ele , -1);
        $element->setCount($count);
        
        $form = $element->getForm(true);
        
        /**
         * If the element is a child form, we have to add the parent's name and
         * count to the form elements.
         * @todo refractor the baseElement's code so that we dont need to do this here.
         */
        $parents = preg_replace("/(-)?{$ele}.*$/" , '' , $refItem );
        if($parents){
            $belongsTo = "";
            if(preg_match("/^\w+-\d+/" , $parents , $matches)){
                $parentCount = explode('-' , $matches[0]);
                $belongsTo = $belongsTo . "{$parentCount[0]}[{$parentCount[1]}]";
                $parents = preg_replace("/^\w+-\d+/", '' , $parents);
            } else if(preg_match("/^\w+/" , $parents , $matches)) {
                $belongsTo = $belongsTo . $matches[0];
                $parents = preg_replace("/^\w+/" , '' , $parents);
            }
            if(preg_match_all("/\w+-\d+/" , $parents , $matches)){
                foreach($matches[0] as $parent){
                    $parentCount = explode('-' , $parent);
                    $belongsTo = $belongsTo . "[{$parentCount[0]}][{$parentCount[1]}]";
                }
            }
            $formBelongsTo = $form->getElementsBelongTo();
            $formBelongsTo = preg_replace('/(^\w+)/' , '[$1]' , $formBelongsTo) ;
            $belongsTo = $belongsTo . $formBelongsTo;
            $form->setElementsBelongTo($belongsTo);
        }
        $partialPath = Zend_Registry::get('config')->resources->layout->layoutpath;
        $myView = new Zend_View;
        $myView->setScriptPath($partialPath.'/partial');
        $myView->assign('form' , $form);
        $form = $myView->render('form.phtml');
        print $form;
        exit;
    }
    
    public function removeFormAction()
    {
        $elementClass = $this->_getParam('classname');
        $id = $this->_getParam('id');
        
        $elementName =  "Iati_Aidstream_Element_".$elementClass;
        $element = new $elementName();
        $element->deleteElement($id);
        exit;
    }
    
    public function elementAction()
    {  
        // Get element id
        $element_id = $this->_request->getQuery('id');
        // Get index of an array
        $index = $this->_request->getQuery('index');
        // Get class name
        $elementClass = $this->_request->getQuery('className');        
        // Fetch element data
        $elementName =  "Iati_Aidstream_Element_Activity_".$elementClass;
        $element = new $elementName();
        $elementData = $element->fetchData($element_id);
        $this->view->data = $elementData[$element->getClassName()][0];
        $this->view->className = strtolower($element->getClassName());
    }
    
    public function previousDocumentsAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $accountId = $identity->account_id;
        
        $model = new Model_UserDocument();
        $docs = $model->fetchAllDocuments($accountId);
        
        $this->view->docs = $docs;
        
        $this->_helper->layout->disableLayout();
    }
    
    public function documentUploadAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $accountId = $identity->account_id;
        
        $model = new Model_UserDocument();
        
        $form = new Form_Wep_DocumentUpload();
        if($data = $this->getRequest()->getPost()){
            //var_dump($data );exit;
            if($form->isValid($data)){
                $uploadDir = Zend_Registry::get('config')->public_folder."/files/documents/";
                $upload = new Zend_File_Transfer_Adapter_Http();
                $upload->setDestination($uploadDir);
                $source = $upload->getFileName();
                try{
                    $upload->receive();
                    $model->saveDocumentInfo(basename($source));
                    $this->view->docUrl = "http://" . $_SERVER['HTTP_HOST']
                        . $this->view->baseUrl()."/files/documents/"
                        .basename($source);
                } catch(Zend_File_Transfer_Exception $e) {
                    $e->getMessage();
                }
            } else {
                $form->populate($data);
            }
        }
        $docs = $model->fetchAllDocuments($accountId);
        
        $this->view->form = $form;
        $this->view->docs = $docs;
        
        $this->_helper->layout->disableLayout();
    }
    
    public function getCountryAction()
    {
        $code = $this->_getParam('code');
        $id = $this->_getParam('id');
        if($code){
            $model = new Model_Wep();
            $country = $model->findIdByFieldData('Country' , $code , 1);
            echo $country[0]['id'];exit;
        } else if($id) {
            $model = new Model_Wep();
            $country  = $model->fetchValueById('Country' , $id , 'Name');
            echo strtolower($country); exit;
        } else {
            echo "Unknown";exit;
        }
    }

    public function changeStateAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $userId = $identity->user_id;

        $stateId = $this->_getParam('id');
        if ($stateId > 0 && $stateId <=4) {
            $userModel = new Model_User();
            $userModel->updateHelpState($stateId);
        }
        exit;
    } 
}
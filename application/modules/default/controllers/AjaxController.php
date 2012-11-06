<?php
/**
 * Controller to handle all the ajax requests like add form, remove form.
 * @author YIPL dev team
 */
class AjaxController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
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
         * If the element is a child form, we have to add the parent's name and count to the form elements.
         * @todo refractor the baseElement's code so that we dont need to do this here.
         */
        if($element->getFullName() != $element->getClassName()){
            foreach(explode('_' , $elementClass) as $parent){
                $eleform = $form;
                if(preg_match("/^{$parent}-\d+/" , $refItem , $matches)){
                    $parentCount = explode('-' , $matches[0]);
                    $belongsTo = $belongsTo . "{$parentCount[0]}[{$parentCount[1]}]";
                } else if (preg_match("/{$parent}-\d+$/" , $refItem , $matches)){
                    $parentCount = explode('-' , $matches[0]);
                    $count = ++$parentCount[1];
                    $belongsTo = $belongsTo . "[{$parentCount[0]}][{$count}]";
                         
                } else if (preg_match("/{$parent}-\d+/" , $refItem , $matches)){
                    $parentCount = explode('-' , $matches[0]);
                    $belongsTo = $belongsTo . "[{$parentCount[0]}][{$parentCount[1]}]";
                  
                } else if(preg_match("/^{$parent}/" , $refItem)) {
                    $belongsTo = $belongsTo . "$parent";
                    
                } else {
                    $belongsTo = $belongsTo . "[$parent]";
                }
            }
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
        $element->deleteElement(array($id));
        exit;
    }
}
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
}
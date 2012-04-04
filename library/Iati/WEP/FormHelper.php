<?php

class Iati_WEP_FormHelper {

    protected $objects = array();
    protected $registryTree;
    protected $ajaxCall = false;
    protected $indexValues = array();
    protected $parentNames;

    public function __construct () {

        $this->registryTree = Iati_WEP_TreeRegistry::getInstance();

        $fc = Zend_Controller_Front::getInstance();
        $this->baseurl = 'http://' . $_SERVER['HTTP_HOST'].$fc->getBaseUrl();

    }

    public function getFormWithAjax ($parents, $items) {

        $this->ajaxCall = true;
        if(!$parents || preg_match('/Transaction|Result/',$parents[0])){
            $this->parentNames = $parents;
            $form = $this->genForm($this->registryTree->getRootNode(), null , $items);
            return $form;
        }
        // this code needs to be refactored as it is modified only for "Conditions" element
        // this is done because the case of conditions is unique, and is not handled right now
        // the parent element i.e. Conditions occures only once (multiple = false) whereas,
        // the child element Condition occures multiple times
        // so when the "add more" is done, the parent is also found and extra "[]" is added
        // so remove this problem the parent portion is removed from the array $parents which is $parent[0]
        if($parents[0] == 'Conditions'){
            $parents = array(); $parents[0] = 'Condition';
        }

        //======================================================================================

        $this->parentNames = $parents;
//        print_r($parents);exit;

        $items = $this->incrementIndex($items);

        //if(count($parents) != count($items)){
        //    $count = count($parents) - 1;
        //    $reversed = array_reverse($parents);
        //    $newArray = array();
        //    foreach($reversed as $key => $val){
        //        $newArray[$val] = $items[$key];
        //    }
        //    $this->indexValues = array_reverse($newArray);
        //}
        //else{
            foreach($this->parentNames as $key => $val) {

                $this->indexValues[$this->parentNames[$key]] = $items[$key];
            }
        //}
        //
        //foreach($this->indexValues as $key => $val){
        //    if($val == ''){
        //        unset($this->indexValues[$key]);
        //    }
        //}

        //$this->indexValues = $items;
        //print_r($this->parentNames);
        //print_r($this->indexValues);


        $root = $this->registryTree->getRootNode();
        $finalHtml = $this->genHtml(array($root));

        $this->generateForm($root, $finalHtml);
        return $finalHtml;
    }

    public function getForm() {

        $form_string = $this->_form($this->registryTree->getRootNode()->getClassName(), '#');
        $root = $this->registryTree->getRootNode();
        $_mainEle = $this->registryTree->getChildNodes($root);
        if('Transaction' == $_mainEle[0]->getClassName() || 'Result' == $_mainEle[0]->getClassName()){
            if($_mainEle[0]->getMultiple()){
                $form = new Iati_Form();
                foreach($_mainEle as $mainEle){
                    $eleForm = $this->genForm($mainEle , null , null);
                    $eleForm->removeDecorator('form');
                    $formName = $eleForm->getElementsBelongTo();
                    //Add main element form to main form
                    $form->addSubForm($eleForm , "{$formName}");
                }
                $className = $mainEle->getClassName();

                $add = new Iati_Form_Element_Note('add');
                $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'add-element button'));
                $add->setValue("<a href='#' class='button' value='$className'> Add More</a>");

                $form->addElement($add);
                $form->addSubmitButton('Save');
                $this->addWrapper($form , $_mainEle[0]);

                return $form;
            } else {
                $form = $this->genForm($_mainEle[0] , null , $count);
                $form->addSubmitButton('Save');
                $this->addWrapper($form , $_mainEle[0]);
            }
        }
        $finalHtml = $this->genHtml(array($root));

        $this->generateForm($root, $finalHtml);
        //$form = $this->genForm();
        $form = sprintf($form_string, $finalHtml);

        //$this->genForm();
        //return $this->_wrap(implode('', ), 'div');

        return $form;
    }

    /**
     * Function to generate form
     */
    public function genForm($mainEle , $parentForm = null , $eleCount = null)
    {
        if(!$eleCount){
            $eleCount = $mainEle->getObjectId();
        }
        if(!$parentForm){
            $className = $mainEle->getClassName();
            $camelCaseToSeperator = new Zend_Filter_Word_CamelCaseToSeparator(" ");
            $title = $camelCaseToSeperator->filter($className);
            if($this->ajaxCall){
                if($this->parentNames){
                    $parentnames = $this->parentNames;
                    $parentsArray = array();
                    $belongs = '';
                    foreach($this->parentNames as $parent){
                        $temp = explode('-' , $parent);
                        $parentsArray[] = $temp[0];
                        if(!$belongs){
                            $belongs = "{$temp[0]}[{$temp[1]}]";
                        } else {
                            $belongs .= "[{$temp[0]}][{$temp[1]}]";
                        }
                    }
                    $parentname = implode('_' ,$parentsArray );
                    $className = $parentname."_".$className;
                    $belongs .= "[{$mainEle->getClassName()}][{$eleCount}]";
                } else {
                    $belongs = "{$mainEle->getClassName()}[{$eleCount}]";
                }
            }
            $formName = 'Iati_WEP_Form_'.$className;
            //Create form
            $form = new $formName();
            if($this->ajaxCall){
                $form->setElementsBelongTo("{$belongs}");
            } else {
                $form->setElementsBelongTo("{$className}[{$eleCount}]");
            }
            $attrs = $this->getAttrib($mainEle , $eleCount);
            $form->populate($attrs);

            $this->getChildForms($form , $mainEle);
            if($mainEle->hasMultiple()){
                $eleName = str_replace('Iati_WEP_Activity_Elements_' , '' , get_class($mainEle));
                $remove = new Iati_Form_Element_Note('remove');
                $removeUrl = Zend_Controller_Front::getInstance()->getBaseUrl()."/wep/remove-elements?classname={$eleName}";
                $remove->setValue("<a href='{$removeUrl}' class='remove-element button'> Remove This</a>");
                $remove->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'remove button'));
                $form->addElements(array($remove));
            }
            if($this->ajaxCall){
                $form->removeDecorator('form');
            }
            return $form;
        } else {
            //Create form
            $elementClass = $mainEle->getClassName();
            $formClassName = preg_replace('/Activity_Elements/','Form',get_class($mainEle));
            $formValues = $this->getAttrib($mainEle, $eleCount);
            $subForm = $parentForm->addSubElement($formClassName , $mainEle , $eleCount , $formValues);

            $this->getChildForms($subForm , $mainEle);

            if($mainEle->hasMultiple()){
                $eleName = str_replace('Iati_WEP_Activity_Elements_' , '' , get_class($mainEle));
                $remove = new Iati_Form_Element_Note('remove');
                $removeUrl = Zend_Controller_Front::getInstance()->getBaseUrl()."/wep/remove-elements?classname={$eleName}";
                $remove->setValue("<a href='{$removeUrl}' class='remove-element button'> Remove This</a>");
                $remove->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'remove button'));
                $subForm->addElements(array($remove));
            }

            return $subForm;
        }
    }

    public function getChildForms($form , $mainEle){
        //Create child forms
        $class = preg_replace('/Iati_WEP_Activity_Elements_/' , '' , get_class($mainEle));
        $tree = new Iati_WEP_ActivityTreeMapper();
        $childElements = $tree->getActivityTree($class);
        if($childElements){
            foreach($childElements as $element){
                $eleName = str_replace($class."_", '' , $element);
                $children = $this->registryTree->getChildNodes($mainEle , $eleName);
                if(!empty($children) ){
                    $hasMultiple = $children[0]->hasMultiple();
                    if($hasMultiple){
                        $subEleForm = new Iati_Form();
                        $subEleForm->removeDecorator('form');
                    }
                    foreach($children as $key => $child){
                        if($hasMultiple){
                            $eleForm = $this->genForm($child , $subEleForm , null);
                        } else {
                            $eleForm = $this->genForm($child , $form , null);
                            $this->addWrapper($eleForm , $children[0]);
                        }
                    }
                    if($hasMultiple){
                        $add = new Iati_Form_Element_Note('add');
                        $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'add-element button'));
                        $add->setValue("<a href='#' class='button' value='$eleName'> Add More</a>");
                        $subEleForm->addElement($add);

                        $this->addWrapper($subEleForm , $children[0]);
                        $form->addSubForm($subEleForm , $eleName);
                    }
                }
            }
        }
    }

    public function addWrapper($form , $element){
        if($element->hasMultiple()){
            foreach($form->getSubForms() as $tmpForm){
                $formClass = $tmpForm->getAttrib('class');
            }
        } else {
            $formClass = $form->getAttrib('class');
        }
        $camelCaseToSeperator = new Zend_Filter_Word_CamelCaseToSeparator(" ");
        $title = $camelCaseToSeperator->filter($element->getElementDisplayName());
        if($element->isRequired()){
            $title .= " * ";
        }
        $form->addDecorators( array(
                    array( 'wrapper' => 'HtmlTag' ), array( 'tag' => 'fieldset' , 'options' => array('legend' => $title , 'class' => $formClass))
                    )
        );
        $form->addDecorators( array(array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'element-wrapper'))));
    }

    public function genHtml ($nodes) {

        $html = array();

        $finalNodes = $this->groupElements($nodes);

        foreach ($finalNodes as $ele) {
            $_ht = array();
            $_title = '';
            if ($this->ajaxCall) {
                if ($ele[0] != $this->registryTree->getRootNode()) {
                    $camelCaseToSeperator = new Zend_Filter_Word_CamelCaseToSeparator(" ");
                    $title = $camelCaseToSeperator->filter($ele[0]->getElementDisplayName());

                    if ($ele[0]->isRequired()) {
                        $title .= '<span class="required">*</span>';
                    }
                    $_title = sprintf('<legend class="form-title">%s</legend>', $title);

                }
            }
            else {
                $camelCaseToSeperator = new Zend_Filter_Word_CamelCaseToSeparator(" ");
                $title = $camelCaseToSeperator->filter($ele[0]->getElementDisplayName());


                if ($ele[0]->isRequired()) {
                    $title .= '<span class="required">*</span>';
                }
                $_title = sprintf('<legend class="form-title">%s</legend>', $title);

            }
//print_r($_title);

            if (!$this->ajaxCall && $ele[0] == $this->registryTree->getRootNode()) {
                $_title = '';
            }

            if ($_title != '') {
                $_ht[] = $_title;
            }


            $_cls = '';

            foreach($ele as $key => $obj) {
                $_ht[] = $this->myForm($obj);

                $addMoreCond = ($obj == $this->registryTree->getRootNode()
                && $this->ajaxCall) ? false : true;

                if ($key == (sizeof($ele) - 1) && $obj->hasMultiple() && $addMoreCond) {

                    $url = $this->getUrl($obj, '/wep/clone-node');

                    $_ht[] =
                    $this->_addMore(array("href" => $url, "class" => 'button'), "a");
                }

                $_childs = $this->registryTree->getChildNodes($obj);
                if ($_childs != NULL) {
                    foreach ($_childs as $key => $val) {
                        if ($this->registryTree->getChildNodes($val) == NULL) {
                            $_cls = 'inner-wrapper';
                        }
                        else {
                            $_cls = '';
                            break;
                        }
                    }
                }
                else {
                    $_cls = 'innermost-wrapper';
                }

                if(!$this->ajaxCall && $this->registryTree->getRootNode() == $this->registryTree->getParentNode($obj)){
                    $_cls .= ' top-element';
                }
            }

            $_html = implode('', $_ht);

            $html[] = (sizeof($_ht) > 1) ?
            sprintf('<fieldset class="form-group %s">%s</fieldset>', $_cls, $_html) : $_html;
        }

        $htmls = (sizeof($html) > 1) ?
        sprintf('<div class="nested-items">%s</div>', implode('', $html)) :
        implode('', $html);
        return $htmls;
    }

    public function groupElements ($nodes) {
        $return = array();
        foreach ($nodes as $ele) {
            if (array_key_exists($ele->getClassName(), $return)) {
                array_push($return[$ele->getClassName()], $ele);
            }
            else {
                $return[$ele->getClassName()] = array($ele);
            }
        }
        return $return;
    }

    public function myForm($obj)
    {
        $form = "";
        $decorate = new Iati_WEP_FormDecorator($obj, $this->getIndexValues($obj));
        $decoratedHtml = $decorate->html();

        $_tag = 'fieldset';
        $_cls = ($this->registryTree->getChildNodes($obj) == NULL) ? 'innermost' : '';
        $_cls = (!$this->ajaxCall && $obj == $this->registryTree->getRootNode()) ? 'ancestor' : $_cls;
        $_cls = ($this->ajaxCall && $obj == $this->registryTree->getRootNode() && $_cls != 'innermost') ? $_cls . ' inner' : $_cls;

        if (!$this->ajaxCall) {
            $_mainEle = $this->registryTree->getChildNodes($this->registryTree->getRootNode());
            if (sizeof($_mainEle) == 1 && $obj == $_mainEle[0]) {
                $_cls .= ' inner';
            }
        }
        //$_cls = ($this->registryTree->getChildNodes($obj) == NULL) ? 'innermost' : '';


        $form .= sprintf("<%s class=\"%s %s\">", $_tag, $obj->getClassName(), $_cls);

        foreach($decoratedHtml as $eachHtml){
            $form .= "<div class='form-item clearfix'> $eachHtml </div>";
        }

        if ($this->registryTree->getChildNodes($obj) != NULL) {
            $form .= "%(".(string)$this->registryTree->getNodeId($obj).")s";
            //$form .= "<div>%s</div>";
        }

        if ($obj->hasMultiple()) {
            $url = $this->getUrl($obj, '/wep/remove-elements');
            $form .= sprintf('<span class="remove button"><a  class="button remove-this" href="%s">Remove this</a></span>',
            $url);
        }

        $form .= sprintf("</%s>", $_tag);
        return $form;

    }

    public function generateForm ($root, &$result='') {
        $childs = $this->registryTree->getChildNodes($root);

        if ($childs != NULL) {

            $parentId = $this->registryTree->getNodeId($root);
            $result = sprintfn2($result,
            array($parentId => $this->genHtml($childs)));
            foreach($childs as $obj) {
                $this->generateForm($obj, $result);
            }
        }
    }

    public function getIndexValues ($obj) {
        $index = array();
        if ($this->ajaxCall) {
            $index = $this->indexValues;
        }
        $parentObjects = $this->registryTree->getParents($obj);
        foreach ($parentObjects as $par) {
            $parName = $par->getClassName();
            if ($this->ajaxCall) {
                if (!array_key_exists($parName, $index) && $par->hasMultiple()) {
                    $index[$parName] = $par->getObjectId();
                    //array_push($index, $parName);
                }
            }
            else {
                if (!array_key_exists($parName, $index) && $par != $this->registryTree->getRootNode() && $par->hasMultiple()) {
                    $index[$parName] = $par->getObjectId();
                }
            }
        }

        if ($obj->hasMultiple() && !array_key_exists($obj->getClassName(), $index)) {
            $index[$obj->getClassName()] = $obj->getObjectId();
        }

        return $index;
        /*
         if ($this->ajaxCall) {
         return $this->indexValues;
         }
         else {
         $arr = array();
         foreach ($this->registryTree->getParents($object) as $parObj) {
         if ($parObj->hasMultiple()) {
         array_push($arr, $parObj->getObjectId());
         }
         }
         if ($object->hasMultiple()) {
         array_push($arr, $object->getObjectId());
         }

         return $arr;
         }
         */
    }

    private function getUrl($obj, $urlPath) {
        $parents = array();
        //print_r($parents);
        if ($this->ajaxCall) {
            $parents = $this->parentNames;
        }
        $parentObjects = $this->registryTree->getParents($obj);
        foreach ($parentObjects as $par) {
            $parName = $par->getClassName();
            if ($this->ajaxCall) {
                if (!in_array($parName, $parents)) {
                    array_push($parents, $parName);
                }
            }
            else {
                if (!in_array($parName, $parents) && $par != $this->registryTree->getRootNode()) {
                    array_push($parents, $parName);
                }
            }
        }

        //print_r($parents);
        $url = $this->baseurl . $urlPath;
        $urlParts = array();
        foreach ($parents as $key => $par) {
            $urlParts[$key] = 'parent' . $key . '=' . $par;
        }
        array_push($urlParts, 'classname=' . $obj->getClassName());
        $url .= '?' . implode('&', $urlParts);

        return $url;
    }

    private function incrementIndex ($index) {
        $current = (int)array_pop($index) + 1;
        array_push($index, $current);
        return $index;
    }

    private function _form($name, $action, $method="post", $attribs=null) {
        $_form = sprintf('<form id="element-form" name="%s" action="%s" method="%s" %s>',
        $name, $action, $method, $this->_attr($attribs));

        $_form .= '<div id = "form-elements-wrapper " class="activity-list">%s</div>';
        /*if ($this->registryTree->getRootNode()->hasMultiple()) {
         $_form .= $this->_addMore(array('id'=>'add-more'));
         }*/
        $_mainEle = $this->registryTree->getChildNodes($this->registryTree->getRootNode());
        if('ReportingOrg' == $_mainEle[0]->getClassName()){
            $_form .= '<input type="submit" id="Submit_and_view" name="save" value="Save and View" class="form-submit disabled" disabled="disabled"/>';
            $_form .= '<input type="submit" id="Submit" name="save" value="Save" class="form-submit disabled" disabled="disabled"/>';
        } else {
            $_form .= '<input type="submit" id="Submit_and_view" name="save" value="Save and View" class="form-submit"/>';
            $_form .= '<input type="submit" id="Submit" name="save" value="Save" class="form-submit"/>';
        }
        $_form .= '</form>';
        return $_form;
    }

    protected function _wrap($formElement, $tag='p', $attribs=null) {
        return sprintf('<%s %s>%s</%s>', $tag,
        $this->_attr($attribs), $formElement, $tag);
    }

    protected function _addMore($attribs=null, $tag='div', $text='Add more') {
        $text = '<' . $tag . ' ' . $this->_attr($attribs) . '>' . $text . '</' . $tag . '>';
        return sprintf('<span class="addmore button">%s</span>', $text);
    }

    protected function _attr($attribs) {
        $_attrs = array();
        if ($attribs) {
            foreach ($attribs as $key=>$value) {
                array_push($_attrs, $key.'="'.$value.'"');
            }
        }
        return (count($_attrs) > 0 ? implode(' ', $_attrs) : '');
    }

    protected function getAttrib($ele , $key)
    {
        $attrs = $ele->getCleanedData();
        $validData = array();
        if($attrs){
            foreach($attrs as $attribKey => $attribValue){
                $attribKey = preg_replace('/@/' , '' , $attribKey);
                $validData[$key][$attribKey] = $attribValue;
            }
        }
        return $validData;
    }
}
?>

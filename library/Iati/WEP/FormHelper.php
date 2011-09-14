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
        $this->parentNames = $parents;

        //print_r($items);

        $items = $this->incrementIndex($items);

        foreach($this->parentNames as $key => $val) {
            $this->indexValues[$this->parentNames[$key]] = $items[$key];
        }
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

        //$form = implode('', $this->genForm());
        $root = $this->registryTree->getRootNode();
        $finalHtml = $this->genHtml(array($root));

        $this->generateForm($root, $finalHtml);
        //$form = $this->genForm();
        $form = sprintf($form_string, $finalHtml);

        //$this->genForm();
        //return $this->_wrap(implode('', ), 'div');

        return $form;
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
                    $title = $camelCaseToSeperator->filter($ele[0]->getClassName());

                    if ($ele[0]->isRequired()) {
                        $title .= '<span class="required">**</span>';
                    }
                    $_title = sprintf('<legend class="form-title">%s</legend>', $title);

                }
            }
            else {
                $camelCaseToSeperator = new Zend_Filter_Word_CamelCaseToSeparator(" ");
                $title = $camelCaseToSeperator->filter($ele[0]->getClassName());


                if ($ele[0]->isRequired()) {
                    $title .= '<span class="required">**</span>';
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
            $form .= sprintf('<span class="remove button"><a  class="button" href="%s">Remove</a></span>',
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
        //print_r($parents);
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

        $_form .= '<input type="submit" id="Submit" value="Save" class="form-submit"/>';
        $_form .= '</form>';
        return $_form;
    }

    protected function _wrap($formElement, $tag='p', $attribs=null) {
        return sprintf('<%s %s>%s</%s>', $tag,
        $this->_attr($attribs), $formElement, $tag);
    }

    protected function _addMore($attribs=null, $tag='div', $text='Add More') {
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
}
?>

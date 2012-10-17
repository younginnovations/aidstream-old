<?php
/**
 * Base class for iati elements 
 * Coantains all the attributes of the element and the methods for functionalities that can be done
 * for the elements like creating form, saving, retrieving, generating xml etc.
 *
 * @param boolen $isMultiple Should be true if the element can be multiple.
 * @param boolen $isRequired Should be true if the element is a required element.
 * @param String $className The name of the class of the element. Name with space should be used as camelCase without space
 * @param String $displayName The name of the element for display in forms , if same as classname should be left empty.
 * @param Array $data array of the elements data and its child element.
 * @param Array $childElements array of classname of the child elements.If no child, an empty array is present.
 * @param Array $attribs array of attributes names strored in database.
 *      @todo use these in database activities.Currently attributes are directly used from form and fetched data.
 * @param Array $iatiAttribs array of the element's iati attributes. these are used while creating xml
 * @param String $tableName Name of the database table used to store the element's data.
 *
 * @author bhabishyat <bhabishyat@gmail.com>
 */
class Iati_Organisation_BaseElement
{
    protected $isMultiple = false;
    protected $isRequired = false;
    protected $className;
    protected $displayName;
    protected $data;
    protected $childElements = array();
    protected $attribs = array();
    protected $iatiAttribs = array();
    protected $tableName;
    protected $db;
    protected $count;
    
    public function __construct()
    {
        $this->db = new Zend_Db_Table($this->tableName);
    }
    
    public function setCount($count)
    {
        $this->count = $count;
    }
    
    public function getCount()
    {
        return $this->count;
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getIsMultiple()
    {
        return $this->isMultiple;
    }
    
    public function getClassName()
    {
        return $this->className;
    }
    
    /**
     * Function to get the full name of the element i.e name with parent name
     * Fullname can be used to directly create the element.
     */
    public function getFullName()
    {
        $classname = get_class($this);
        $fullname = preg_replace('/Iati_Organisation_Element_/' , '' , $classname);
        return $fullname;
    }
    
    /**
     * Function to get the display name for the element.
     * If display name is present it is returned else classname is returned
     */
    public function getDisplayName()
    {
        if($this->displayName){
            return $this->displayName;
        } else {
            $this->className;
        }
    }
    
    /**
     * Function to get the child elements of the element
     */
    public function getChildElements()
    {
        return $this->childElements;
    }
    
    /**
     * Function to get the form for the element.
     * 
     * This function creates the element's own form,
     * creates child elements if present , calls getForm() of the child element and
     * adds the child form as the subform of the element's form
     *
     * @param boolen $ajax True if the form fetch is done using ajax
     * @return form for the element with children's forms added as subform.
     */
    public function getForm($ajax = false)
    {
        $formname = preg_replace('/Element/' , 'Form' , get_class($this));
        if($this->data){
            if($this->isMultiple){
                $form = new Iati_Organisation_WrapperForm();
                foreach($this->data as $data){
                    $eleForm = new $formname(array('element' => $this));
                    $eleForm->setData($data);
                    $elementForm = $eleForm->getForm();
                    $childElements = $this->getChildElements();
                    if(!empty($childElements)){
                        $elementForm = $this->addChildForms($childElements , $elementForm , $data);
                    }
                    // add remove to form
                    $elementForm = $this->addRemoveLink($elementForm);

                    $elementForm->removeDecorator('form');
                    $elementForm->prepare();

                    $form->addSubForm($elementForm , $this->getClassName().$elementForm->getCount($this->getClassName()));
                }

                // add add button to wrapper form;
                $form = $this->addAddLink($form);
                
            } else {
                $eleForm = new $formname(array('element' => $this));
                $form = $eleForm->getForm();
                $childElements = $this->getChildElements();
                if(!empty($childElements)){
                    $form = $this->addChildForms($childElements , $form , $this->data);
                }
                $form->prepare();
            }
        } else {
            if($this->isMultiple){
                $form = new Iati_Organisation_WrapperForm();
                $eleForm = new $formname(array('element' => $this));
                if($this->count){
                    $eleForm->setCount($this->count);
                }
                $elementForm = $eleForm->getForm();
                
                $childElements = $this->getChildElements();
                if(!empty($childElements)){
                    $elementForm = $this->addChildForms($childElements , $elementForm);
                }
                 // add remove to form
                $elementForm = $this->addRemoveLink($elementForm);
                
                $elementForm->removeDecorator('form');
                $elementForm->prepare();
                
                // If the form build is called using ajax return the form without preparing it further.
                if($ajax){
                    return $elementForm;
                }
                
                $form->addSubForm($elementForm , $this->getClassName());
                
                // add add button to wrapper form;
                $form = $this->addAddLink($form);
                
            } else {
                $eleForm = new $formname(array('element' => $this));
                if($this->count){
                    $eleForm->setCount($this->count);
                }
                $form = $eleForm->getFormDefination();
                $childElements = $this->getChildElements();
                if(!empty($childElements)){
                    $form = $this->addChildForms($childElements , $form);
                }
                $form->prepare();
            }
        }
        $this->_wrapForm($form);
        return $form;
    }
    
    /**
     * Function to add child forms from child elements of an element.
     *
     * This function loops through all child elements, creates elements for each child, calls getForm for the child
     * and adds the form returned by the child to its form.
     * @param Array $childElements array of child classnames.
     * @param Object Form object to which child are to be added.
     * @param Array Data of the element and its children.
     */
    public function addChildForms($childElements , $form , $data = array())
    {
        foreach($childElements as $childElementClass){
            $childElementName = get_class($this)."_$childElementClass";
            $childElement = new $childElementName();
            if(!empty($data)){
                $childElement->setData($data[$childElementClass]);
            }
            $childForm = $childElement->getForm();
            $childForm->removeDecorator('form');
            $form->addSubForm($childForm , $childElementClass.$childForm->getCount($childElementClass));
        }   
        return $form;
    }
    
    /**
     * Function to add 'remove element' link to the form for element which can be multiple.
     *
     * @param Object Form object to which remove link is to be added.
     * @return Object Form object of form with remove link added.
     */
    public function addRemoveLink($form)
    {
        $remove = new Iati_Form_Element_Note('remove');
        $remove->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'remove-element element-remove-this'));
        $remove->setValue("<a href='#' class='button' value='{$this->getFullName()}'> Remove element</a>");
        $form->addElement($remove);
        
        return $form;
    }
    
    /**
     * Function to add 'add more' link to form for element which can be multiple.
     *
     * @param Object Form object to which add more link is to be added.
     * @return Object Form object with add more link added.
     */
    public function addAddLink($form)
    {
        $add = new Iati_Form_Element_Note('add');
        $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'add-more element-add-more'));
        $add->setValue("<a href='#' class='button' value='{$this->getFullName()}'> Add More</a>");
        $form->addElement($add);
        
        return $form;
    }
    
    /**
     * Function to add fieldset and wrapper div to the form
     */
    protected function _wrapForm($form)
    {        
        $form->addDecorators( array(
                    array( 'wrapper' => 'HtmlTag' ),
                    array( 'tag' => 'fieldset' , 'options' => array('legend' => $this->getDisplayName()))
                )
        );
        $form->addDecorators( array(array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'element-wrapper'))));
        return $form;
    }
    
    /**
     * Function to save the element's and its childrens data.
     * 
     * This function saves the elements data into its table (inserts/updates the element's data),
     * creates child elements if present and calls save for the child elements
     * @param $data data of the element and its childrens
     * @param $parent array of parent column as key and its value as the array value. If the element is the
     * topmost element i.e has no parent the parameter is an empty array.
     */
    public function save($data , $parent = array())
    {        
        $parentName = array_pop(array_keys($parent));
        if($this->isMultiple){
            foreach($data as $elementData){
                $elementsData = $this->getElementsData($elementData);
                if(!empty($parent)){
                    $elementsData[$parentName."_id"] = $parent[$parentName];
                }
                
                // If no id is present, insert the data else update the data using the id.
                if(!$elementsData['id']){
                    $elementsData['id'] = null;
                    $id[$this->convertCamelCaseToUnderScore($this->className)] = $this->db->insert($elementsData);
                } else {
                    $eleId = $elementsData['id'];
                    unset($elementsData['id']);
                    if($elementsData){
                        $this->db->update($elementsData , array('id = ?' => $eleId));
                    }
                    $id[$this->convertCamelCaseToUnderScore($this->className)] = $eleId;
                }
                
                // If children are present create children elements and call their save function.                
                if(!empty($this->childElements)){
                    foreach($this->childElements as $childElementClass){
                        $childElementName = get_class($this)."_$childElementClass";
                        $childElement = new $childElementName();
                        $childElement->save($elementData[$childElementClass] , $id);
                    }
                }
            }
        } else {
            $elementsData = $this->getElementsData($data);
            if(!empty($parent)){
                $elementsData[$parentName."_id"] = $parent[$parentName];
            }
            
            // If no id is present, insert the data else update the data using the id.
            if(!$elementsData['id']){
                $elementsData['id'] = null;
                $id[$this->convertCamelCaseToUnderScore($this->className)] = $this->db->insert($elementsData);
            } else {
                $eleId = $elementsData['id'];
                unset($elementsData['id']);
                $this->db->update($elementsData , array('id = ?' => $eleId));
                $id[$this->convertCamelCaseToUnderScore($this->className)] = $eleId;
            }
            
            // If children are present create children elements and call their save function.
            if(!empty($this->childElements)){
                foreach($this->childElements as $childElementClass){
                    $childElementName = get_class($this)."_$childElementClass";
                    $childElement = new $childElementName();
                    $childElement->save($data[$childElementClass] , $id);
                }
            }
        }
        return $id[$this->convertCamelCaseToUnderScore($this->className)];
    }

    /**
     * Function to get the data for the elements attribs form the elements and its childrens data.
     */
    public function getElementsData($data)
    {
        foreach($this->attribs as $attrib){
            $elementsData[$attrib] = $data[$attrib];
        }
        return $elementsData;
    }
    
    /**
     * Function to fetch the element and its childrens' data.
     * 
     * @param $key array with columnname as array key and its value as the array value.
     * If the element is child or should be fetched by parent id, the key is the parent classname
     * else the key is not specified and id column is used.
     * e.g array('myElementClass' => '2'), array(2)
     */    
    public function fetchData($key)
    {
        $parentName = array_pop(array_keys($key));
        $eleId = array_pop($key);
        if($this->isMultiple){
            // If parentName is present use it to fetch using the parent column.
            if($parentName){
                $parentColumn = $this->convertCamelCaseToUnderScore($parentName);
                $parentColumn .= "_id";
                $eleData = $this->db->fetchAll($this->db->getAdapter()->quoteInto("{$parentColumn} = ?" , $eleId ));
            } else { // If parentName is not present the provided id is its own id so fetch by own id.
                $eleData = $this->db->fetchAll($this->db->getAdapter()->quoteInto("id = ?" , $eleId));
            }
            if($eleData){
                $data = $eleData->toArray();
            }
            // If children is present fetch their data.
            if(!empty($this->childElements)){
                foreach($data as $key=>$elementData){
                    foreach($this->childElements as $childElementClass){
                        $childElementName = get_class($this)."_$childElementClass";
                        $childElement = new $childElementName();
                        $data[$key][$childElementClass] = $childElement->fetchData(array($this->className => $elementData['id']));
                    }
                }
            }
        } else {
            if($parentName){
                $parentColumn = $this->convertCamelCaseToUnderScore($parentName);
                $parentColumn .= "_id";
                $select = $this->db->select()->where($this->db->getAdapter()->quoteInto("{$parentColumn} = ?" , $eleId));
            } else {
                $select = $this->db->select()->where($this->db->getAdapter()->quoteInto("id = ?" , $eleId));
            }
            $row = $this->db->fetchRow($select);
            if($row){
                $data = $row->toArray();
            }
            if(!empty($this->childElements)){
                foreach($this->childElements as $childElementClass){
                    $childElementName = get_class($this)."_$childElementClass";
                    $childElement = new $childElementName();
                    $data[$childElementClass] = $childElement->fetchData(array($this->className => $data['id']));
                }
            }
        }
        // For data consistency return data as element classname as key and element's data as value.
        // Only needed if doesnot have parent, as classname as key is already used to insert to parent data.
        if(!$parentName){
            $returnData[$this->className] = $data;
        } else {
            $returnData = $data;
        }
        return $returnData;
    }
    
    /**
     * Function to delete the element and its childrens.
     * 
     * @param $key array with columnname as array key and its value as the array value.
     * If the element is child or should be deleted by parent, the key is the parent_key coulumn
     * else key is not specified and id column is used.
     * e.g array('myClassName' => 2) , array(2)
     */
    public function deleteElement($key)
    {
        $parentName = array_pop(array_keys($key));
        $eleId = array_pop($key);
        if($parentName){
            $parentColumn = $this->convertCamelCaseToUnderScore($parentName);
            $parentColumn .= "_id";
            if($this->childElements){
                // get the ids of the elements from the parent id so that the elements ids can be passed to the children.
                $elementIds = $this->getElementIdsFromParent($parentColumn , $eleId);
                // Foreach element delete their children.
                foreach($elementIds as $elementId){
                    foreach($this->childElements as $childElementClass){
                        $childElementName = get_class($this)."_$childElementClass";
                        $childElement = new $childElementName();
                        $childElement->deleteElement(array($this->className => $elementId['id']));
                    }
                }
            }
            
            $where = $this->db->getAdapter()->quoteInto("{$parentColumn} = ?", $eleId);
            $this->db->delete($where);
        } else {
            // If children are present first delete the children.
            if($this->childElements){
                foreach($this->childElements as $childElementClass){
                    $childElementName = get_class($this)."_$childElementClass";
                    $childElement = new $childElementName();
                    $childElement->deleteElement(array($this->className => $eleId));
                }
            }
            $where = $this->db->getAdapter()->quoteInto("id = ?", $eleId);
            $this->db->delete($where);
        }
    }
    
    /**
     * Function to fetch id of the elements from the parent columns.
     * Used by delete function to get elements' ids so that their children can be deleted from their id.
     */
    public function getElementIdsFromParent($parentColumn , $parentId)
    {
        $select = $this->db->select()->from($this , array('id'))->where("$parentColumn = ?" , $parentId);
        $ids = $this->db->fetchAll($select);
        if($ids){
            return $ids->toArray();
        } else {
            return false;
        }
    }
    
    /**
     * Function to convert camel case classnames to names seperated by underscore.
     *
     * @param String $className Camel cased classname string.
     * @return String string with words in class name string joined by underscore.
     */
    public function convertCamelCaseToUnderScore($className)
    {
        $underscore= strtolower(preg_replace('/([^A-Z_])([A-Z])/', '$1_$2', $className));
        return $underscore;
    }
    
    /**
     * Function to get the xml of the element.
     *
     * The function checks for the parent. If the parent is not present, it creates a simpleXMlElement
     * object with its name as the element name, adds attribute and calls to children's getXml if any.
     * If parent is present, it adds a child with the elements name as the name to the parent, adds
     * attribute and calls children's getXml if any.
     * @param Array $key array with parent name as array key and id as array value.
     *         If not called by parent, array key is empty.
     * @param Object_SimpleXMLElement/null If called by parent, the parameter is the parent object else null.
     * @return Object_SimpleXMLElement/null
     */
    public function getXml($key , $parent = null)
    {
        $eleName = $this->getXmlName();        
        
        $parentName = array_pop(array_keys($key));
        $eleId = array_pop($key);
        if($this->isMultiple){
            // If parentName is present use it to fetch using the parent column.
            if($parentName){
                $parentColumn = $this->convertCamelCaseToUnderScore($parentName);
                $parentColumn .= "_id";
                $eleData = $this->db->fetchAll($this->db->getAdapter()->quoteInto("{$parentColumn} = ?" , $eleId ));
            } else { // If parentName is not present the provided id is its own id so delete by own id.
                $eleData = $this->db->fetchAll($this->db->getAdapter()->quoteInto("id = ?" , $eleId));
            }
            if($eleData){
                $data = $eleData->toArray();
            }
            foreach($data as $row){
                if(!is_object($parent)){
                    $xmlObj = new SimpleXMLElement("<$eleName>".$row['text']."</$eleName>");
                } else {
                    $xmlObj = $parent->addChild($eleName , $row['text']);
                }
                $xmlObj = $this->addElementsXmlAttribsFromData($xmlObj , $row);

            }
            // If children is present fetch their data.
            if(!empty($this->childElements)){
                foreach($data as $key=>$elementData){
                    foreach($this->childElements as $childElementClass){
                        $childElementName = get_class($this)."_$childElementClass";
                        $childElement = new $childElementName();
                        $childElement->getXml(array($this->className => $elementData['id']) , $xmlObj);
                    }
                }
            }
        } else {
                       
            if($parentName){
                $parentColumn = $this->convertCamelCaseToUnderScore($parentName);
                $parentColumn .= "_id";
                $select = $this->db->select()->where($this->db->getAdapter()->quoteInto("{$parentColumn} = ?" , $eleId));
            } else {
                $select = $this->db->select()->where($this->db->getAdapter()->quoteInto("id = ?" , $eleId));
            }
            $row = $this->db->fetchRow($select);
            if($row){
                $data = $row->toArray();
            }
            if(!is_object($parent)){
                $xmlObj = new SimpleXMLElement("<$eleName>".$data['text']."</$eleName>");
            } else {
                $xmlObj = $parent->addChild($eleName , $data['text']);
            }
            
            $xmlObj = $this->addElementsXmlAttribsFromData($xmlObj , $data);
            
            if(!empty($this->childElements)){
                foreach($this->childElements as $childElementClass){
                    $childElementName = get_class($this)."_$childElementClass";
                    $childElement = new $childElementName();
                    $childElement->getXml(array($this->className => $data['id']) , $xmlObj);
                }
            }
        }
        
        return $xmlObj;
    }
    
    /**
     * Function to fetch the xml name of the element.
     * It can also be used to convert classnames or camelCased names to xml-names by passing the name as argument.
     *
     * @param $name className or camelCased names if present is converted to xml-names
     * else the element's xmlname is fetched.
     */
    public function getXmlName($name = '')
    {
        if(!$name){
            if($this->xmlName){
                $name = $this->xmlName;
            } else {
                $name = $this->className;
            }
        }
        $name = $this->convertCamelCaseToUnderScore($name);
        $name = preg_replace('/_/' , '-' , $name);
        return $name;
    }
    
    /**
     * Loops through the data and adds each attribute to the simpleXmlObject.
     * @param $xmlObj SimpleXMLElement object to which attribute is to be added.
     * @param $data array of attribs with attrib name as key.
     */
    public function addElementsXmlAttribsFromData($xmlObj , $data)
    {
        foreach($data as $name=>$value){  
            if(in_array($name , $this->iatiAttribs) && $name != 'text')
            {
                if($name == "xml_lang"){
                    $name = preg_replace('/_/',':',$name);
                    $xmlObj->addAttribute($name , $value , "http://www.w3.org/XML/1998/namespace");
                } elseif ($name == 'last_updated_datetime'){
                    // Convert last updated date to UTC format
                    $name = preg_replace('/_/','-',$name);
                    $gmDateValue = gmdate('c' , strtotime($value));
                    $xmlObj->addAttribute($name,$gmDateValue);
                }
                else {
                    $name = preg_replace('/_/','-',$name);
                    $xmlObj->addAttribute($name,$value);
                }

            }
        }
        return $xmlObj;
    }
}

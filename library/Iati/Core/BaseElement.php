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
class Iati_Core_BaseElement
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
    protected $viewScriptEnabled = false;
    protected $viewScript;


    public function __construct()
    {
        $this->db = new Zend_Db_Table($this->tableName);
    }
    
    public function setCount($count)
    {
        $this->count = $count;
    }
    
    public function getIsRequired()
    {
        return $this->isRequired;
    }
    
    public function getCount()
    {
        return $this->count;
    }
    
    public function setIsMultiple($isMultiple)
    {
        $this->isMultiple = $isMultiple;
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
    public function getTableName()
    {
        return $this->tableName;
    } 
    /**
     * Function to get the full name of the element i.e name with parent name
     * Fullname can be used to directly create the element.
     */
    public function getFullName()
    {
        $classname = get_class($this);
        $fullname = preg_replace('/Iati_Aidstream_Element_/' , '' , $classname);
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
                $form = new Iati_Core_WrapperForm();
                foreach($this->data as $data){
                    $eleForm = new $formname(array('element' => $this));
                    $eleForm->setData($data);
                    $elementForm = $eleForm->getForm();
                    $childElements = $this->getChildElements();
                    if(!empty($childElements)){
                        $elementForm = $this->addChildForms($childElements , $elementForm , $data);
                    }

                    $elementForm->removeDecorator('form');
                    $elementForm->prepare();

                    $form->addSubForm($elementForm , $this->getClassName().$elementForm->getCount($this->getClassName()));
                }

                // add add button to wrapper form;
                $form->addAddLink($this->getFullName());
                
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
                $form = new Iati_Core_WrapperForm();
                $eleForm = new $formname(array('element' => $this));
                if($this->count){
                    $eleForm->setCount($this->count);
                }
                $elementForm = $eleForm->getForm();
                
                $childElements = $this->getChildElements();
                if(!empty($childElements)){
                    $elementForm = $this->addChildForms($childElements , $elementForm);
                }
                
                $elementForm->removeDecorator('form');
                $elementForm->prepare();
                
                // If the form build is called using ajax return the form without preparing it further.
                if($ajax && !$this->viewScriptEnabled){
                    return $elementForm;
                }
                
                $form->addSubForm($elementForm , $this->getClassName());
                
                // add add button to wrapper form;
                $form->addAddLink($this->getFullName());
                
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
        if($this->viewScriptEnabled){
            $viewScriptFile = ($this->viewScript) ? $this->viewScript : (($this->isMultiple) ? 'default/multiple.phtml' : 'default/single.phtml');
            $form->setDecorators(array(
                                    array('ViewScript', array(
                                                                'viewScript' => $viewScriptFile ,
                                                                'display' => $this->getDisplayName() ,
                                                                'eleLevel' => $this->getLevel() ,
                                                                'ajax' => $ajax
                                                            )
                                        )
                                    )
                                 );
            return $form;
        }
        $form->wrapForm($this->getDisplayName() , $this->getIsRequired());
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
                $childElement->setData($data[$childElement->getClassName()]);
            }
            $childForm = $childElement->getForm();
            $childForm->removeDecorator('form');
            $form->addSubForm($childForm , $childElementClass.$childForm->getCount($childElementClass));
        }   
        return $form;
    }
    
    /**
     * Function to save the element's and its childrens data.
     * 
     * This function saves the elements data into its table (inserts/updates the element's data),
     * creates child elements if present and calls save for the child elements
     * @param $data data of the element and its childrens
     * @param Integer $parentId Id of the parent of the element for fetching the data
     */
    public function save($data , $parentId = null)
    {  
        if($parentId){
            $parentColumnName = $this->getParentCoulmn();
        }
        if($this->isMultiple){
            foreach($data as $elementData){ 
                $elementsData = $this->getElementsData($elementData);
                if($this->hasData($elementData)){
                    if($parentId){
                        $elementsData[$parentColumnName] = $parentId;
                    }
                    // If no id is present, insert the data else update the data using the id.
                    if(!$elementsData['id']){
                        $elementsData['id'] = null;
                        $eleId = $this->db->insert($elementsData);
                    } else {
                        $eleId = $elementsData['id'];
                        unset($elementsData['id']);
                        $this->db->update($elementsData , array('id = ?' => $eleId));
                    }
                } else {
                    if($elementData['id']){
                        $where = $this->db->getAdapter()->quoteInto('id = ?', $elementData['id']);
                        $this->db->delete($where);
                        return;
                    }
                }
                
                // If children are present create children elements and call their save function.                
                if(!empty($this->childElements)){
                    foreach($this->childElements as $childElementClass){
                        $childElementName = get_class($this)."_$childElementClass";
                        $childElement = new $childElementName();
                        $childElement->save($elementData[$childElement->getClassName()] , $eleId);
                    }
                }
            }
        } else {
            $elementsData = $this->getElementsData($data);
            if($this->hasData($data)){
                if($parentId){
                    $elementsData[$parentColumnName] = $parentId;
                }

                // If no id is present, insert the data else update the data using the id.
                if(!$elementsData['id']){
                    $elementsData['id'] = null;
                    $eleId = $this->db->insert($elementsData);
                } else {
                    $eleId = $elementsData['id'];
                    unset($elementsData['id']);
                    $this->db->update($elementsData , array('id = ?' => $eleId));
                }
            } else {
                if($elementsData['id']){
                    $where = $this->db->getAdapter()->quoteInto('id = ?', $elementData['id']);
                    $this->db->delete($where);
                    return;
                }
            }
            // If children are present create children elements and call their save function.
            if(!empty($this->childElements)){
                foreach($this->childElements as $childElementClass){
                    $childElementName = get_class($this)."_$childElementClass";
                    $childElement = new $childElementName();
                    $childElement->save($data[$childElement->getClassName()] , $eleId);
                }
            }
        }
        return $eleId;
    }

    /**
     * Function to get the data for the elements attribs form the elements and its childrens data.
     */
    public function getElementsData($data)
    {
        foreach($this->attribs as $attrib){
            // @todo remove the replace once the @ is removed from columnname.
            $elementsData[$attrib] = $data[preg_replace('/^@/' , '' , $attrib)];
        }
        return $elementsData;
    }
    
    /**
     * Function to check if the supplied array has value for any row.
     */
    public function hasData($data)
    {
        if(!is_array($data)){
            return false;
        }
        foreach($data as $key=>$values){
            if($key == 'id' || $key == 'add' || $key == 'remove' || $key == 'save' || $key == 'save_and_view') continue;// check for empty excluding these elements
            if($values){
                if(is_array($values)){
                    $hasData = $this->hasData($values);
                    if($hasData) return true;
                } else {
                    return true;
                }
            }
        }
        return false; 
    }

    /**
     * Function to fetch the element and its childrens' data.
     * 
     * @param Integer $eleId Id of the element for fetching the data
     * @param Boolen $parent true if the id belongs to the element's parent.
     */    
    public function fetchData($eleId , $parent = false)
    {
        if($parent){
            $parentColumn = $this->getParentCoulmn();
        }
        if($this->isMultiple){
            // If parentName is present use it to fetch using the parent column.
            if($parent){
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
                        $data[$key][$childElement->getClassName()] = $childElement->fetchData($elementData['id'] , true);
                    }
                }
            }
        } else {
            if($parent){
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
                    $data[$childElement->getClassName()] = $childElement->fetchData($data['id'] , true);
                }
            }
        }
        // For data consistency return data as element classname as key and element's data as value.
        // Only needed if doesnot have parent, as classname as key is already used to insert to parent data.
        
        if(!$parent){
            $returnData[$this->className] = $data;
        } else {
            $returnData = $data;
        }
        return $returnData;
    }
    
    /**
     * function to fetch parent id column name to fetch or insert using parent id.
     */
    public function getParentCoulmn()
    {
        $ancestors = explode('_' , $this->getFullName());
        end($ancestors);
        $parentName = prev($ancestors);
        $parentColumn = $this->convertCamelCaseToUnderScore($parentName);
        $parentColumn .= "_id";
        return $parentColumn;
    }
    
    /**
     * Function to delete the element and its childrens.
     * 
     * @param Integer $eleId Id of the element for fetching the data
     * @param Boolen $parent true if the id belongs to the element's parent
     */
    public function deleteElement($eleId , $parent = false)
    {
        if($parent){
            $parentColumn = $this->getParentCoulmn();
            if($this->childElements){
                // get the ids of the elements from the parent id so that the elements ids can be passed to the children.
                $elementIds = $this->getElementIdsFromParent($parentColumn , $eleId);
                // Foreach element delete their children.
                foreach($elementIds as $elementId){
                    foreach($this->childElements as $childElementClass){
                        $childElementName = get_class($this)."_$childElementClass";
                        $childElement = new $childElementName();
                        $childElement->deleteElement($elementId['id'] , true);
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
                    $childElement->deleteElement($eleId , true);
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
        $select = $this->db->select()->from($this->tableName , array('id'))->where("$parentColumn = ?" , $parentId);
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
     * 
     * @param Integer $eleId Id of the element to fetch data (or the parents id)
     * @param Boolen $isParentId true if the element id passed is the parents id.
     * @param Object_SimpleXMLElement/null If called by parent, the parameter is the parent object else null.
     * @return Object_SimpleXMLElement/null
     */
    public function getXml($eleId , $isParentId = false, $parent = null)
    {
        $eleName = $this->getXmlName();
        if($isParentId){
            $parentColumn = $this->getParentCoulmn();
        }
        
        if($this->isMultiple){
            // If parentName is present use it to fetch using the parent column.
            if($isParentId){
                $eleData = $this->db->fetchAll($this->db->getAdapter()->quoteInto("{$parentColumn} = ?" , $eleId ));
            } else { // If parentName is not present the provided id is its own id so fetch by own id.
                $eleData = $this->db->fetchAll($this->db->getAdapter()->quoteInto("id = ?" , $eleId));
            }

            if($eleData){
                $data = $eleData->toArray();
            } else {
                return; // xml should not be added if data is not present.
            }
            
            foreach($data as $row){
                if(!is_object($parent)){
                    $xmlObj = new SimpleXMLElement("<$eleName>".preg_replace('/&(?!\w+;)/' , '&amp;' , $row['text'])."</$eleName>");
                } else {
                    $xmlObj = $parent->addChild($eleName , preg_replace('/&(?!\w+;)/' , '&amp;' , $row['text']));
                }
                $xmlObj = $this->addElementsXmlAttribsFromData($xmlObj , $row);
                // If children is present fetch their data.
                if(!empty($this->childElements)){
                    foreach($this->childElements as $childElementClass){
                        $childElementName = get_class($this)."_$childElementClass";
                        $childElement = new $childElementName();
                        $childElement->getXml($row['id'] , true , $xmlObj);
                    }
                }
            }
        } else {          
            if($isParentId){
                $select = $this->db->select()->where($this->db->getAdapter()->quoteInto("{$parentColumn} = ?" , $eleId));
            } else {
                $select = $this->db->select()->where($this->db->getAdapter()->quoteInto("id = ?" , $eleId));
            }
            $row = $this->db->fetchRow($select);
            
            if($row){
                $data = $row->toArray();
            } else { 
                return; // xml should not be added if data is not present.
            }
            
            if(!is_object($parent)){
                $xmlObj = new SimpleXMLElement("<$eleName>".preg_replace('/&(?!\w+;)/' , '&amp;' ,$data['text'])."</$eleName>");
            } else {
                $xmlObj = $parent->addChild($eleName , preg_replace('/&(?!\w+;)/' , '&amp;' ,$data['text']));
            }
            
            $xmlObj = $this->addElementsXmlAttribsFromData($xmlObj , $data);
            
            if(!empty($this->childElements)){
                foreach($this->childElements as $childElementClass){
                    $childElementName = get_class($this)."_$childElementClass";
                    $childElement = new $childElementName();
                    $childElement->getXml($data['id'] , true , $xmlObj);
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
                if(!$value) continue;
                $name = preg_replace("/^@/" , '' , $name);
                if($name == "xml_lang"){
                    $value = Iati_Core_Codelist::getCodeByAttrib($this->className, '@xml_lang' , $value);
                    $name = preg_replace('/_/',':',$name);
                    $xmlObj->addAttribute($name , $value , "http://www.w3.org/XML/1998/namespace");
                    
                }elseif($name == "currency" || $name == "default_currency"){ 
                    $value = Iati_Core_Codelist::getCodeByAttrib("Activity_default", '@currency' , $value);
                    $name = preg_replace('/_/','-',$name);
                    $xmlObj->addAttribute($name,$value);
                    
                }elseif ($name == 'last_updated_datetime'){
                    // Convert last updated date to UTC format
                    $name = preg_replace('/_/','-',$name);
                    $gmDateValue = gmdate('c' , strtotime($value));
                    if($gmDateValue)    $xmlObj->addAttribute($name,$gmDateValue);
                }
                else {
                    $value = Iati_Core_Codelist::getCodeByAttrib($this->className, $name , $value);
                    $name = preg_replace('/_/','-',$name);
                    $xmlObj->addAttribute($name,$value);
                }

            }
        }
        return $xmlObj;
    }
    
    public function getLevel()
    {
        $names = explode('_' , $this->getFullName());
        return array_search($this->getClassName() , $names);
    }
}

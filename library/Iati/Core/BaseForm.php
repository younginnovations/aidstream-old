<?php
/**
 * Base class for Creating element forms, Extends Zend_Form
 *
 * This Class acts as the base for extending by the elements' forms. It provides basic functionalities
 * like setting data, element, preparing the form etc.
 * 
 * To create form for any element only the getFormDefination should be overridden with the element's form defination.
 * 
 * @param Object $element Object of the element for which the form is defined.
 * @param Array $data Data of the element for which the form is defined.
 * @param boolen $isMultiple true if the multiple element is present else false.
 *
 * @author bhabishyat <bhabishyat@gmail.com>
 */
abstract class Iati_Core_BaseForm extends Zend_Form
{
    protected $_defaultDisplayGroupClass = 'Iati_Form_DisplayGroup';
    protected $element;
    protected $data;
    protected $isMultiple;
    public static $count = array();
    
    abstract public function getFormDefination();
    
    /**
     * Overriding default form decorators.
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FormElements')
                ->addDecorator('Form')
                ->addDecorators( array(array(array( 'wrapper' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'form-wrapper'))));
        }
    }
    
    /**
     * Overriding zend form render.
     */
    public function render()
    {
        $requiredSuffx = '<span title="This field is required." class="form-required">*</span>';

        foreach ($this->getElements() as $element) {
            $decorator = $element->getDecorator('Label');
            if ($decorator) {
                if ($element->getLabel()) { // need to check this, since label decorator can be blank
                    $element->setLabel($element->getLabel() . "&nbsp;");
                }
                $decorator->setOption('requiredSuffix', $requiredSuffx);
                $decorator->setOption('escape', false);
            }
            if ($element->getErrors()) {
                $this->addElementClass($element, 'error');
            }

            // Add a wrapper div to all elements other than add and remove buttons.
            if($element->getName() != 'add' && $element->getName() != 'remove' && $element->getType() != 'Zend_Form_Element_Submit'){

                if($element->getName() == 'id' || ($element->getType() == 'Zend_Form_Element_Hidden' && preg_match('/_id/' , $element->getName()))){
                    $element->addDecorators(array(
	                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'form-item ele-id clearfix'))
	                    )
                    );
                } else if($element->getName() == 'save_and_view' || $element->getName() == 'save'){
                     $element->addDecorators(array(
	                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'form-item ele-submit-buttons clearfix'))
	                    )
                    );
                } else {
                    // Add help element
                     $uniqueElementName = $this->element->getFullName().'-'.$element->getName();
                     $element->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help '.$uniqueElementName , 'placement' => 'PREPEND'))));
	                
                     $element->addDecorators(array(
	                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'form-item clearfix'))
	                    )
	                );
                }
            } else {
                $element->removeDecorator('label');
            }
        }

        $output = parent::render();
        return $output;
    }
    
    public function addElementClass(&$element, $className, $decorator = null)
    {
        if (!$element instanceof Zend_Form_Element) {
            $element = $this->getElement($element);
        }

        if ($decorator) {
            $decoratorObj = $element->getDecorator($decorator);
            $origClass = $decoratorObj->getOption('class');
            $newClass = $origClass . ' ' . $className;
            $decoratorObj->setOption('class', $newClass);
        } else {
            $origClass = $element->getAttrib('class');
            $newClass = $origClass . ' ' . $className;
            $element->setAttrib('class', $newClass);
        }

        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function setCount($count)
    {
        self::$count[$this->element->getClassName()] = $count;
    }
    
    public function getIatiElement()
    {
        return $this->element;
    }
    
    public function countElement($element)
    {
        if(!self::$count[$element]){
            self::$count[$element] = 0;
        }
        $elecount = self::$count[$element];
        self::$count[$element] = self::$count[$element] + 1;
        return $elecount;
    }
    
    public function getCount($element)
    {
        $elecount = self::$count[$element];
        return $elecount;
    }
    
    public function setMultiple($multiple = false)
    {
        $this->isMultiple = $multiple;
    }
    
    /**
     * Function to set the element attribute of the class.
     * It also sets the data and isMultiple attribute from the element's attributes
     */
    public function setElement($element)
    {
        $this->element = $element;
        $this->setData($element->getData());
        $this->setMultiple($element->getIsMultiple());
    }
    
    /**
     * Function to fetch the form for the element.
     *
     * This function fetches the element's form defination and updates the count of the element.
     */
    public function getForm()
    {
        $form = $this->getFormDefination();
        if($this->data){
            $form->populate($this->data);
        }
        $count = $this->countElement($this->element->getClassName());
        return $form;
    }
    
    /**
     * Function to prepare the form ,add element names to the form.
     * Uses count and element name for creating array of element.
     */
    public function prepare()
    {
        if($this->isMultiple) {
            $this->addRemoveLink();
            $this->setIsArray(true);
            $this->setElementsBelongTo("{$this->element->getClassName()}[{$this->getCount($this->element->getClassName())}]");
        } else {
            $this->setElementsBelongTo("{$this->element->getClassName()}");
        }
    }
    
    /**
     * Function to add 'remove element' link to the form for element which can be multiple.
     *
     * @param String $className full name of the element for which remove link is added.
     *      If not provided, the fullname is fetched from the form's element.
     */
    public function addRemoveLink($className = '')
    {
        if(!$className){
            $className = $this->element->getFullName();
        }
        $remove = new Iati_Form_Element_Note('remove');
        $remove->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'organisation-remove-element element-remove-this'));
        $remove->setValue("<a href='#' class='button' value='{$className}'> Remove this</a>");
        $this->addElement($remove);        
    }
    
    /**
     * Function to add 'add more' link to form for element which can be multiple.
     *
     * @param String $classname to which add more link is to be added.
     */
    public function addAddLink($className)
    {
        $add = new Iati_Form_Element_Note('add');
        $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'add-more element-add-more'));
        $add->setValue("<a href='#' class='button' value='{$className}'> Add more</a>");
        $this->addElement($add);        
    }
    
     /**
     * Function to add fieldset and wrapper div to the form
     * @param String $displayName The name of the element to be used for fieldset legend.
     * @param Boolen $isRequired Ture if element is required false otherwise
     */
    public function wrapForm($displayName, $isRequired = false)
    {
        if($isRequired){
            $displayName = $displayName . " *";
        }
        $this->addDecorators( array(
                    array( 'wrapper' => 'HtmlTag' ),
                    array( 'tag' => 'fieldset' , 'options' => array('legend' => $displayName))
                )
        );
        $this->addDecorators( array(array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'element-wrapper'))));
    }
    
    /**
     * Function to add submit buttons to the form.
     *
     * Removes any submit button if present before and adds 'save' and 'save and view' buttons
     * @param String $label If the label of the save button should be different, an string should be passed.
     * @param String $saveAndViewlabel If the label of the save and view should be different, an string should be passed.
     */
    public function addSubmitButton($label , $saveAndViewlabel = 'Save and View')
    {
        if($this->submit){
            $this->removeElement('sumbit');
        }
        $this->addElement('submit' , 'save_and_view',
            array(
                'label' => $saveAndViewlabel,
                'required' => false,
                'ignore'   => false,
            )
        );
        $this->addElement('submit' , 'save',
            array(
                'label'    => $label,
                'required' => false,
                'ignore'   => false,
            )
        );
    }
    
    /**
     * Custom validation
     * 
     * If form has no subform i.e lowest level form, check if form is empty if not then only validate the form
     *  If form has subforms first validate each subform(Subform of each type is wrapped by a wrapper)
     *  Then validate each element.
     */
    public function validate(){
        $isValid = true;
        if(!$this->getSubForms()){
           return $this->validateForm();
        } else {      
            foreach($this->getSubForms() as $subform){
                if(!$subform->validate()){
                    $isValid = false;
                }
                if (!($this instanceof Iati_Core_WrapperForm)){
                    $valid = $this->validateForm();
                    if(!$valid){
                        $isValid = false;
                    }
                }
            }
        }
        return $isValid;
    }
    
    public function validateForm()
    {
        $hasChildData = $this->element->hasData($this->element->getData());
        if($hasChildData || $this->element->getIsRequired()){
            $isValid = true;
            foreach($this->getElements() as $element){
                if(!$element->isValid($element->getValue())){
                    $isValid = false;
                }
            }
            return $isValid;
        } else {
            foreach ($this->getElements() as $element){
                $data[$element->getName()] = $element->getValue();
            }
            unset($data['remove']);
            unset($data['add']);
            $empty = true;
            if(!empty($data)){
                foreach($data as $key=>$value){
                    if($value && $key !== 'id'){
                        $empty = false;
                    }
                }
            }
            if(!$empty){
                $isValid = true;
                foreach($this->getElements() as $element){
                    if(!$element->isValid($element->getValue())){
                        $isValid = false;
                    }
                }
                return $isValid;
            }
        }
        return true;
    }
}
<?php
class Simplified_Form_Activity_Location extends Iati_SimplifiedForm
{
    protected $data;
    protected $count = 0;
    
    public function init()
    {
        parent::init();
        $this->setAttrib('class' , 'simplified-sub-element')
            ->setIsArray(true);
            
        $model = new Model_Wep();
        $form = array();
        
        $form['location_id'] = new Zend_Form_Element_Hidden('location_id');
        $form['location_id']->setValue($this->data['location_id']);
        
        $form['location_name_id'] = new Zend_Form_Element_Hidden('location_name_id');
        $form['location_name_id']->setValue($this->data['location_name_id']);
        
        $this->data['location_name'] = 'Morang';
        $nameData = $this->data['location_name'];
        $form['location_name'] = new Zend_Form_Element_Select('location_name');
        $form['location_name']->setLabel('District Name')
            ->setRequired()
            ->addMultiOption($nameData , $nameData)
            ->setValue($nameData)
            ->setRegisterInArrayValidator(false)
            ->setAttrib('class' , 'location level-1 form-select')
            ->setAttrib('style' , 'width:300px');
            
        $form['location_adm2_id'] = new Zend_Form_Element_Hidden('location_adm2_id');
        $form['location_adm2_id']->setValue($this->data['location_adm2_id']);
        
        $this->data['location_adm2'] = array('Amahibariyati' , 'Babiyabirta');
        $adm2Data = $this->data['location_adm2'];
        foreach($adm2Data as $data){
            $options[$data] = $data;
        }
        $form['location_adm2'] = new Zend_Form_Element_Select('location_adm2');
        $form['location_adm2']->setLabel('VDC name')
            ->addMultiOptions($options)
            ->setValue($adm2Data)
            ->setRegisterInArrayValidator(false)
            ->setAttrib('multiple'  , true)
            ->setAttrib('class' , 'location level-2 form-select');
            
        $this->addElements($form);

        $this->setElementsBelongTo("location[{$this->count}]");
        // Add remove button
        $remove = new Iati_Form_Element_Note('remove');
        $remove->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-remove-element'));
        $remove->setValue("<a href='#' class='button' value='Location'> Remove element</a>");
        $this->addElement($remove);
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function setCount($count)
    {
        $this->count = $count;
    }
}
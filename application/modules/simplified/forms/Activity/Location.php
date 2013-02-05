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
        
        $form['location_desc_id'] = new Zend_Form_Element_Hidden('location_desc_id');
        $form['location_desc_id']->setValue($this->data['location_desc_id']);
        
        $form['location_coord_id'] = new Zend_Form_Element_Hidden('location_coord_id');
        $form['location_coord_id']->setValue($this->data['location_coord_id']);
        
        $form['location_coord_lat'] = new Zend_Form_Element_Hidden('location_coord_lat');
        $form['location_coord_lat']->setValue($this->data['location_coord_lat']);
        
        $form['location_coord_long'] = new Zend_Form_Element_Hidden('location_coord_long');
        $form['location_coord_long']->setValue($this->data['location_coord_long']);
        
        $form['location_adm_id'] = new Zend_Form_Element_Hidden('location_adm_id');
        $form['location_adm_id']->setValue($this->data['location_adm_id']);
        
        $form['location_adm_adm1'] = new Zend_Form_Element_Hidden('location_adm_adm1');
        $form['location_adm_adm1']->setValue($this->data['location_adm_adm1']);
        
        $form['location_adm_adm2'] = new Zend_Form_Element_Hidden('location_adm_adm2');
        $form['location_adm_adm2']->setValue($this->data['location_adm_adm2']);
        
        $form['location_name_id'] = new Zend_Form_Element_Hidden('location_name_id');
        $form['location_name_id']->setValue($this->data['location_name_id']);        
        
        $nameData = $this->data['location_name'];
        $form['location_name'] = new Zend_Form_Element_Select('location_name');
        $form['location_name']->setLabel('District Name')
            ->setRequired()
            ->addMultiOption($nameData , $nameData)
            ->setValue($nameData)
            ->setRegisterInArrayValidator(false)
            ->setAttrib('class' , 'location level-1 form-select')
            ->setAttrib('style' , 'width:300px');
        
        //$this->data['location_vdcs'] = 'Amahibariyati , Babiyabirta';
        $adm2Data = $this->data['location_vdcs'];
        $adm2Data = explode(',' , $adm2Data);
        $adm2Data = preg_replace('/ /' , '' , $adm2Data);
        foreach($adm2Data as $data){
            $options[$data] = $data;
        }
        $form['location_vdcs'] = new Zend_Form_Element_Select('location_vdcs');
        $form['location_vdcs']->setLabel('VDC name')
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
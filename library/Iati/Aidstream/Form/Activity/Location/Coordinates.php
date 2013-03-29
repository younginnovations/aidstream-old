<?php

class Iati_Aidstream_Form_Activity_Location_Coordinates extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
            
        $form['latitude'] = new Zend_Form_Element_Text('latitude');
        $form['latitude']->setLabel('Latitude')
            ->setRequired()    
            ->setValue($this->data['@latitude'])
            ->setAttribs(array('class' => 'form-text'));
        
        $form['longitude'] = new Zend_Form_Element_Text('longitude');
        $form['longitude']->setLabel('longitude')
            ->setRequired()    
            ->setValue($this->data['@longitude'])
            ->setAttribs(array('class' => 'form-text'));
        
        $percisionCode = $model->getCodeArray('PercisionCode', null, '1' , true);
        $form['percision'] = new Zend_Form_Element_Select('percision');
        $form['percision']->setLabel('Percision')  
            ->setValue($this->data['@percision'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($percisionCode);

        $this->addElements($form);
        return $this;
    }
}
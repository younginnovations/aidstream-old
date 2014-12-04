<?php

class Iati_Aidstream_Form_Activity_Location_Point_Pos extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {   
        $this->setAttrib('class' , 'simplified-sub-element');
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
            
        $form['latitude'] = new Zend_Form_Element_Text('latitude');
        $form['latitude']->setLabel('Latitude')
            ->setRequired()    
            ->setValue($this->data['@latitude'])
            ->setAttribs(array('class' => 'form-text'));
        
        $form['longitude'] = new Zend_Form_Element_Text('longitude');
        $form['longitude']->setLabel('Longitude')
            ->setRequired()    
            ->setValue($this->data['@longitude'])
            ->setAttribs(array('class' => 'form-text'));

        $this->addElements($form);
        return $this;
    }
}
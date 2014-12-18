<?php

class Iati_Aidstream_Form_Activity_Location_Point extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {   
        $this->setAttrib('class' , 'simplified-sub-element');

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
            
        $form['srsName'] = new Zend_Form_Element_Text('srsName');
        $form['srsName']->setLabel('srsName')
            ->setRequired()
            ->setValue("http://www.opengis.net/def/crs/EPSG/0/4326")
            ->setAttribs(array('class' => 'form-text'))
            ->setAttribs(array('readonly' => 'readonly'));

        $this->addElements($form);
        return $this;
    }
}
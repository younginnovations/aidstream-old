<?php

class Iati_Aidstream_Form_Activity_OtherActivityIdentifier_OwnerOrg extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {   
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
            
        $form['ref'] = new Zend_Form_Element_Text('ref');
        $form['ref']->setLabel('Reference')   
            ->setValue($this->data['@ref'])
            ->setAttribs(array('class' => 'form-text'));
      

        $this->addElements($form);
        return $this;
    }
}
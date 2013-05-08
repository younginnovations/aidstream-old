<?php

class Iati_Aidstream_Form_Activity_Location_Administrative extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
            
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setRequired()
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));
        
        $countryCode = $model->getCodeArray('Country', null, '1' , true);
        $form['country'] = new Zend_Form_Element_Select('country');
        $form['country']->setLabel('Country')  
            ->setValue($this->data['@country'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($countryCode);
        
        $form['adm1'] = new Zend_Form_Element_Textarea('adm1');
        $form['adm1']->setLabel('Admin-1')  
            ->setValue($this->data['@adm1'])
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));
        
        $form['adm2'] = new Zend_Form_Element_Textarea('adm2');
        $form['adm2']->setLabel('Admin-2')  
            ->setValue($this->data['@adm2'])
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));

        $this->addElements($form);
        return $this;
    }
}
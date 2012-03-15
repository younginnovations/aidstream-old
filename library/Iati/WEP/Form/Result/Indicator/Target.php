<?php

class Iati_WEP_Form_Result_Indicator_Target extends Iati_Form
{
    public function init()
    {
        $model = new Model_Wep();

        $this->setAttrib('class' , 'second-child')
            ->setMethod('post')
            ->setIsArray(true);
            
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        
        $form['value'] = new Zend_Form_Element_Text('value');
        $form['value']->setLabel('Value')
            ->setAttrib('class' , 'form-text');
            
        $form['year'] = new Zend_Form_Element_Text('year');
        $form['year']->setLabel('Year')
            ->setAttrib('class' , 'form-text');
        
        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($lang);
            
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'));
        
        $this->addElements($form);
    }
}
<?php

class Iati_Aidstream_Form_Activity_Description extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setRequired()    
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'));
        
        $descriptionType = $model->getCodeArray('DescriptionType', null, '1' , true);
        $form['type'] = new Zend_Form_Element_Select('type');
        $form['type']->setLabel('Description Type')  
            ->setValue($this->data['@type'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($descriptionType);
            
        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->setValue($this->data['@xml_lang'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($lang);

        $this->addElements($form);
        return $this;
    }
}
<?php

class Iati_Aidstream_Form_Activity_RelatedActivity extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Activity Title')  
            ->setValue($this->data['text'])  
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));
        
        $relatedActivityType = $model->getCodeArray('RelatedActivityType', null, '1' , true);
        $form['type'] = new Zend_Form_Element_Select('type');
        $form['type']->setLabel('Type of Relationship')  
            ->setValue($this->data['@type'])
            ->setRequired()
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($relatedActivityType);
        
        $form['ref'] = new Zend_Form_Element_Textarea('ref');
        $form['ref']->setLabel('Activity Identifier')  
            ->setValue($this->data['@ref'])
            ->setRequired()    
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));
        
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
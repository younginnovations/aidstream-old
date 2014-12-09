<?php

class Iati_Aidstream_Form_Organisation_DocumentLink_RecipientCountry extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();

        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
            
        $lang = $model->getCodeArray('Country', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel(' Recipient Country')
            ->setRequired()  
            ->setValue($this->data['@code'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($lang);
        
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Title')
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'));    

        $this->addElements($form);
        return $this;
    }
}
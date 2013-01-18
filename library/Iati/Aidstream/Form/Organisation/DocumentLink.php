<?php

class Iati_Aidstream_Form_Organisation_DocumentLink extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['url'] = new Zend_Form_Element_Text('url');
        $form['url']->setLabel('Url')
            ->setAttribs(array('class' => 'form-text'))
            ->setRequired()
            ->setValue($this->data['@url']);
            
        $format = $model->getCodeArray('FileFormat', null, '1' , true);
        $form['format'] = new Zend_Form_Element_Select('format');
        $form['format']->setLabel('Format')
            ->setValue($this->data['@format'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($format);

        $this->addElements($form);
        return $this;
    }
}
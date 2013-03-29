<?php

class Iati_Aidstream_Form_Activity_DocumentLink extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['url'] = new Zend_Form_Element_Textarea('url');
        $form['url']->setLabel('Document Url')
            ->addValidator(new App_Validate_Url)    
            ->setAttribs(array('class' => 'form-text'))
            ->setRequired()
            ->setValue($this->data['@url'])
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));     
            
        $format = $model->getCodeArray('FileFormat', null, '1' , true);
        $form['format'] = new Zend_Form_Element_Select('format');
        $form['format']->setLabel('Document Format')
            ->setValue($this->data['@format'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($format);

        $this->addElements($form);
        return $this;
    }
}
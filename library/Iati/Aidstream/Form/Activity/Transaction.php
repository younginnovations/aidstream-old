<?php

class Iati_Aidstream_Form_Activity_Transaction extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        parent::init();
        $this->setAttrib('id' ,'transaction')
            ->setAttrib('class' , 'top-element collapsable')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');

        $form['ref'] = new Zend_Form_Element_Text('ref');
        $form['ref']->setLabel('Reference')
            ->addfilters(array('StringTrim' , 'StringToLower'))
            ->setAttribs(array('class' => 'form-text'))
            ->setValue($this->data['@ref'])
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-ref' , 'placement' => 'PREPEND'))));

        $this->addElements($form);
        return $this;
    }
    
    public function addSubmitButton($label , $saveAndViewlabel = 'Save and View')
    {
        $this->addElement('submit' , 'update',
            array(
                'label'    => 'update',
                'required' => false,
                'ignore'   => false,
            )
        );
    }
}
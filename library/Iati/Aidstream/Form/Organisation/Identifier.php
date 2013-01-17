<?php

class Iati_Aidstream_Form_Organisation_Identifier extends Iati_Core_BaseForm
{

    public function getFormDefination()
    {
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
                ->setValue($this->data['text'])
                ->setAttrib('cols', '40')
                ->setAttrib('rows', '2')
                ->setAttribs(array('disabled' => 'disabled'))
                ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help Organisation_Identifier-text' , 'placement' => 'PREPEND'))));

        $this->addElements($form);
        return $this;

    }

}
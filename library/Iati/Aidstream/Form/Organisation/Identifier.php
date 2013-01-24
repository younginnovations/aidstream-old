<?php

class Iati_Aidstream_Form_Organisation_Identifier extends Iati_Core_BaseForm
{

    public function getFormDefination()
    {   
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Iati Identifier')
                ->setValue($this->data['text'])
                ->setRequired()
                ->setAttrib('cols', '40')
                ->setAttrib('rows', '2')
                ->setAttribs(array('disabled' => 'disabled'))
                ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help Organisation_Identifier-text' , 'placement' => 'PREPEND'))));

        $this->addElements($form);
        $this->setAction($baseurl.'/organisation/update-default/?elementName=Identifier');
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
<?php

class Iati_Aidstream_Form_Organisation_ReportingOrg extends Iati_Core_BaseForm
{

    public function getFormDefination()
    {   
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['@ref'] = new Zend_Form_Element_Text('@ref');
        $form['@ref']->setLabel('Ref')
                ->setValue($this->data['Ref'])
                ->setAttribs(array('disabled' => 'disabled'))
                ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help Organisation_ReportingOrg-ref' , 'placement' => 'PREPEND'))));

        $form['type'] = new Zend_Form_Element_Text('type');
        $form['type']->setLabel('Type')
                ->setValue($this->data['text'])
                ->setAttribs(array('disabled' => 'disabled'))
                ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help Organisation_ReportingOrg-type' , 'placement' => 'PREPEND'))));
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
                ->setValue($this->data['text'])
                ->setAttrib('cols', '40')
                ->setAttrib('rows', '2')
                ->setAttribs(array('disabled' => 'disabled'))
                ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help Organisation_ReportingOrg-text' , 'placement' => 'PREPEND'))));

        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
                ->setValue($this->data['@xml_lang'])
                ->setAttrib('class' , 'form-select')
                ->setAttribs(array('disabled' => 'disabled'))                
                ->addMultioptions($lang)
                ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help Organisation_ReportingOrg-xml_lang' , 'placement' => 'PREPEND'))));

        $this->addElements($form);
        $this->setAction($baseurl.'/organisation/update-default/?elementName=ReportingOrg');
        return $this;

    }

}
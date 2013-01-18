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
                ->setValue($this->data['@ref'])
                ->setAttribs(array('disabled' => 'disabled'))
                ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help Organisation_ReportingOrg-ref' , 'placement' => 'PREPEND'))));
        
        $reportingOrgType = $model->getRowsByFields('OrganisationType','id', $this->data['@type']);
        $form['type'] = new Zend_Form_Element_Select('type');
        $form['type']->setLabel('Type')
                ->setAttrib('class' , 'form-select')
                ->addMultiOptions(array($this->data['@type']=>$reportingOrgType[0]['Name']))
                ->setAttribs(array('disabled' => 'disabled'))
                ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help Organisation_ReportingOrg-type' , 'placement' => 'PREPEND'))));

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
                ->setValue($this->data['text'])
                ->setAttrib('cols' , '40')
                ->setAttrib('rows' , '2')
                ->setAttribs(array('disabled' => 'disabled'))
                ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help Organisation_ReportingOrg-text' , 'placement' => 'PREPEND'))));

        $lang = $model->getRowsByFields('Language','id', $this->data['@xml_lang']);
        $form['xml_lang'] = new Zend_Form_Element_Select('@xml_lang');
        $form['xml_lang']->setLabel('Language')
                ->setAttrib('class' , 'form-select')
                ->setAttribs(array('disabled' => 'disabled'))
                ->addMultioptions(array($this->data['@xml_lang']=>$lang[0]['Code'].'-'.$lang[0]['Name']))
                ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help Organisation_ReportingOrg-xml_lang' , 'placement' => 'PREPEND'))));

        $this->addElements($form);
        $this->setAction($baseurl . '/organisation/update-default/?elementName=ReportingOrg');
        return $this;

    }

    public function addSubmitButton($label , $saveAndViewlabel = 'Save and View')
    {
        $this->addElement('submit' , 'update' , array(
            'label' => 'update' ,
            'required' => false ,
            'ignore' => false ,
                )
        );

    }

}
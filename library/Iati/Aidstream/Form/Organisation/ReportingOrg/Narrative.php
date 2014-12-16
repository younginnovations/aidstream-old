<?php

class Iati_Aidstream_Form_Organisation_ReportingOrg_Narrative extends Iati_Core_BaseForm

{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Name')
                >setValue($this->data['text'])
                ->setAttrib('cols' , '40')
                ->setAttrib('rows' , '2')
               ->setAttribs(array('disabled' => 'disabled'));

        $lang = $model->getRowsByFields('Language','id', $this->data['@xml_lang']);
        $form['xml_lang'] = new Zend_Form_Element_Select('@xml_lang');
        $form['xml_lang']->setLabel('Language')
               ->setAttrib('class' , 'form-select')
                ->setAttribs(array('disabled' => 'disabled'))
                ->addMultioptions(array($this->data['@xml_lang']=>$lang[0]['Code'].'-'.$lang[0]['Name']));


        $this->addElements($form);
        return $this;
    }
}
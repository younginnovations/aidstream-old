<?php

class Iati_Aidstream_Form_Activity_Transaction_DisbursementChannel extends Iati_Core_BaseForm
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

        $codes = $model->getCodeArray('DisbursementChannel', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Disbursement Channel Code')
            ->setValue($this->data['@code'])
            ->setRequired()
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($codes)
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-disbursement_channel-code' , 'placement' => 'PREPEND'))));

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'))
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-disbursement_channel-text' , 'placement' => 'PREPEND'))));
        
        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->setValue($this->data['@xml_lang'])
            ->setAttrib('class' , 'form-select')
            ->addMultioptions($lang)
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-disbursement_channel-xml_lang' , 'placement' => 'PREPEND'))));

        $this->addElements($form);
        return $this;
    }
}
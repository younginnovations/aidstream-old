<?php

class Iati_WEP_Form_Transaction_DisbursementChannel extends Iati_Form
{
    public function init()
    {
        $model = new Model_Wep();

        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');

        $codes = $model->getCodeArray('DisbursementChannel', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Code')
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($codes)
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-disbursement_channel-code' , 'placement' => 'PREPEND'))));

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'))
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-disbursement_channel-text' , 'placement' => 'PREPEND'))));

        $this->addElements($form);
    }
}
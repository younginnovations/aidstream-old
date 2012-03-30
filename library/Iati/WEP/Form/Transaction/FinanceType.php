<?php

class Iati_WEP_Form_Transaction_FinanceType extends Iati_Form
{
    public function init()
    {
        $model = new Model_Wep();

        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');

        $codes = $model->getCodeArray('FinanceType', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Code')
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($codes)
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-finance_type-code' , 'placement' => 'PREPEND'))));

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'))
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-flow_type-text' , 'placement' => 'PREPEND'))));

        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($lang)
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-flow_type-xml_lang' , 'placement' => 'PREPEND'))));

        $this->addElements($form);
        $this->addDecorators(array(
            array('ViewScript', array('viewScript' => 'transaction/finance-type.phtml'))
        ));
    }
}
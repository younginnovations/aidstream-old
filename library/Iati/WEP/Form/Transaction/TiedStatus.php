<?php

class Iati_WEP_Form_Transaction_TiedStatus extends Iati_Form
{
    public function init()
    {
        $model = new Model_Wep();

        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');

        $codes = $model->getCodeArray('TiedStatus', null, '1' , true);;
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Tied Status Code')
            ->setRequired()
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($codes)
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-tied_status-code' , 'placement' => 'PREPEND'))));

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'))
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-tied_status-text' , 'placement' => 'PREPEND'))));

        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($lang)
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-tied_status-xml_lang' , 'placement' => 'PREPEND'))));

        $this->addElements($form);
    }
}
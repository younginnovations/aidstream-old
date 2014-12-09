<?php

class Iati_Aidstream_Form_Activity_Transaction_FlowType extends Iati_Core_BaseForm
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

        $codes = $model->getCodeArray('FlowType', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Flow Type Code')
            ->setValue($this->data['@code'])
            ->setRequired()
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($codes)
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-flow_type-code' , 'placement' => 'PREPEND'))));

        
        $this->addElements($form);
        return $this;
    }
}
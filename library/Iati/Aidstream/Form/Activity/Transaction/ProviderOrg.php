<?php

class Iati_Aidstream_Form_Activity_Transaction_ProviderOrg extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setRequired()
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'))
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-provider_org-text' , 'placement' => 'PREPEND'))));

        $form['ref'] = new Zend_Form_Element_Text('ref');
        $form['ref']->setLabel('Organisation Identifier Code')
            ->setValue($this->data['@ref'])
            ->setAttrib('class' , 'form-text')
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-provider_org-ref' , 'placement' => 'PREPEND'))));

        $form['provider_activity_id'] = new Zend_Form_Element_Text('provider_activity_id');
        $form['provider_activity_id']->setLabel('Provider Activity Id')
            ->setValue($this->data['@provider_activity_id'])
            ->setAttrib('class' , 'form-text')
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-provider_org-provider_activity_id' , 'placement' => 'PREPEND'))));

        $this->addElements($form);
        return $this;
    }
}
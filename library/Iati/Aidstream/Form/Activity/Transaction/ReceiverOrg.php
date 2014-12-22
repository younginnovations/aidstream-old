<?php

class Iati_Aidstream_Form_Activity_Transaction_ReceiverOrg extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

            

        $form['ref'] = new Zend_Form_Element_Text('ref');
        $form['ref']->setLabel('Organisation Identifier Code')
            ->setValue($this->data['@ref'])
            ->setAttrib('class' , 'form-text')
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-receiver_org-ref' , 'placement' => 'PREPEND'))));

        $form['receiver_activity_id'] = new Zend_Form_Element_Text('receiver_activity_id');
        $form['receiver_activity_id']->setLabel('Receiver Activity Id')
            ->setValue($this->data['@receiver_activity_id'])
            ->setAttrib('class' , 'form-text')
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-receiver_org-receiver_activity_id' , 'placement' => 'PREPEND'))));
        $this->addElements($form);
        return $this;
    }
}
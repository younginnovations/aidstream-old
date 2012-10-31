<?php

class Iati_Organisation_Form_Activity_Transaction_ReceiverOrganisation extends Iati_Organisation_BaseForm
{
    public function getFormDefination()
    {
        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'))
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-receiver_org-text' , 'placement' => 'PREPEND'))));

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
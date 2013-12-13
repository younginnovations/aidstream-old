<?php
class Form_Wep_UploadTransaction extends App_Form
{
    public function init()
    {
	$this->setAttrib('id','upload-transaction-form');
        
        $form = array();
        $form['csv'] = new Zend_Form_Element_File('csv');
        $form['csv']->setLabel('Transaction csv file')
	    ->setRequired(true)
            ->addValidator('Extension', true, array('extension' => 'csv' , 'messages' => 'please use csv format'))
            ->setDescription('Should be in csv format');
	    

        
        $form['submit'] = new Zend_Form_Element_Submit('upload');
        $form['submit']->setLabel('Upload')
            ->setAttrib('class' , 'form-submit');
        
        $this->addElements($form);
    }
}

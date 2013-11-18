<?php
class Form_Wep_DocumentUpload extends App_Form
{
    public function init()
    {
	$this->setAttrib('id','upload-document-form');
	$this->setAttrib('encType' , "multipart/form-data");
        
        $form = array();
        $form['document'] = new Zend_Form_Element_File('document');
        $form['document']->setLabel('Please choose your document')
	    ->setRequired(true);
	$form['document']->getValidator('Upload')
	    ->setMessages(array(Zend_Validate_File_Upload::NO_FILE => "Please choose a file to upload",));

        
        $form['submit'] = new Zend_Form_Element_Submit('upload');
        $form['submit']->setValue('Upload')
            ->setAttrib('class' , 'form-submit');
        
        $this->addElements($form);
    }
}
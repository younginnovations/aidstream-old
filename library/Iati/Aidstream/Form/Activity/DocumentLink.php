<?php

class Iati_Aidstream_Form_Activity_DocumentLink extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

        $model = new Model_Wep();
        
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['url'] = new Zend_Form_Element_Textarea('url');
        $form['url']->setLabel('Document Url')
            ->addValidator(new App_Validate_Url)    
            ->setAttribs(array('class' => 'form-text'))
            ->setRequired()
            ->setValue($this->data['@url'])
            ->setDescription("If your document is not uploaded,
                             <a href='#' class='upload-here'>Upload it</a>
                             in AidStream. You can also add from your
                             <a href='#' class='existing-doc'>existing</a> documents in Aidstream")
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'))
            ->addDecorator('Description' , array('escape' => false , 'class' => 'description'));
            
        $format = $model->getCodeArray('FileFormat', null, '1' , true);
        $form['format'] = new Zend_Form_Element_Select('format');
        $form['format']->setLabel('Document Format')
            ->setRequired()
            ->setValue($this->data['@format'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($format);

        $this->addElements($form);
        return $this;
    }
}
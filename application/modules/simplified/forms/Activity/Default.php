<?php
class Simplified_Form_Activity_Default extends App_Form
{

    public function init(){
        $form = array();

        $form['title'] = new Zend_Form_Element_Text('title');
        $form['title']->setLabel('Activity Title')
            ->setRequired()
            ->setAttrib('class', 'form-text');
            
        $form['description'] = new Zend_Form_Element_Text('description');
        $form['description']->setLabel('Description')
            ->setRequired()
            ->setAttrib('class', 'form-text');
            
        $form['funding_org'] = new Zend_Form_Element_Text('funding_org');
        $form['funding_org']->setLabel('Funding Organisation')
            ->setRequired()
            ->setAttrib('class', 'form-text');

       
        foreach($form as $item_name=>$element)
        {
            $form[$item_name]->addDecorators( array(
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                    )
            );
        }
        
        $form['submit'] = new Zend_Form_Element_Submit('submit');
        $form['submit']->setValue('Save');
        $this->addElements($form);
    }
}

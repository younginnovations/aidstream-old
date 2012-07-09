<?php
class Simplified_Form_Activity_Document extends Iati_SimplifiedForm
{
    protected $data;
    protected $count = 0;
    
    public function init()
    {
        parent::init();
        $this->setAttrib('class' , 'simplified-sub-element')
            ->setIsArray(true);
            
        $model = new Model_Wep();
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $form['url'] = new Zend_Form_Element_Text('url');
        $form['url']->setLabel('Url')
            ->setRequired()
            ->setValue($this->data['url'])
            ->setAttrib('class', 'form-text');
            
        $form['category_id'] = new Zend_Form_Element_Hidden('category_id');
        $form['category_id']->setValue($this->data['category_id']);

        $categoryCodes = $model->getCodeArray('DocumentCategory' , '' , 1 , true);
        $form['category_code'] = new Zend_Form_Element_Select('category_code');
        $form['category_code']->setLabel('Category Code')
            ->setRequired()
            ->addMultiOptions($categoryCodes)
            ->setValue($this->data['category_code'])
            ->setAttrib('class', 'form-select');
            
        $form['title_id'] = new Zend_Form_Element_Hidden('title_id');
        $form['title_id']->setValue($this->data['title_id']);

        $form['title'] = new Zend_Form_Element_Text('title');
        $form['title']->setLabel('Title')
            ->setRequired()
            ->setValue($this->data['title'])
            ->setAttrib('class', 'form-text');
            
        $this->addElements($form);

        $this->setElementsBelongTo("document[{$this->count}]");
        // Add remove button
        $remove = new Iati_Form_Element_Note('remove');
        $remove->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-remove-element'));
        $remove->setValue("<a href='#' class='button' value='DocumentLink'> Remove element</a>");
        $this->addElement($remove);
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function setCount($count)
    {
        $this->count = $count;
    }
}
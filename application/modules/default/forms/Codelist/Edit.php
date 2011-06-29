<?

class Form_Codelist_Edit extends App_Form
{
    public function init(){

    }
    public function edit($fieldName, $tblName, $lang)
    {
        $form = array();
        foreach ($fieldName as $colname) {
            if ($colname == 'Description') {
                $form[$colname] = new Zend_Form_Element_Textarea($colname);
                $form[$colname]
                        ->setAttrib('cols', '45')
                        ->setAttrib('rows', '5');
                $form[$colname]->setLabel($colname);
            } elseif ($colname == 'CategoryCode') {
                $catView = new Model_CategoryManager();
                $result = $catView->getCategory($tblName . "Category", null,$lang,$flag=TRUE);
                $value = $result[0];
                $value = $value->toArray();
                $form[$colname] = new Zend_Form_Element_Select($colname);
                $form[$colname]->addMultiOption('', 'Select');
                foreach ($value as $catData) {
                    $catName = $catData['Name'];
                    $catId = $catData['Code'];
                    $form[$colname]->addMultiOption($catId, $catId . '--' . $catName);
                    $form[$colname]->setLabel($colname);
                }
            } elseif ($lang != 1 && $colname == 'Code') {
                
            } else {
                $form[$colname] = new Zend_Form_Element_Text($colname);
                $form[$colname]->setLabel($colname);
            }
            if ( $colname == 'Code'&& $lang==1)
                $form[$colname]
                        ->setRequired();
            if ( $colname == 'Name' )
                $form[$colname]
                        ->setRequired();
        }
        
        $form['lang_id'] = new Zend_Form_Element_Hidden('lang_id');
        $form['lang_id']->setValue($lang);

        $form['submit'] = new Zend_Form_Element_Submit('Submit');
        $form['submit']->setValue('Submit');

        $this->addElements($form);
        $this->setMethod('post');
    }

}


<?php
class Form_Wep_ActivityDate extends App_Form
{
    public function add($state = "add", $activity_id = '', $account_id = '', $data = array()){
        //        $form = new Zend_Form();

        //        $this->;
        $name = 'activity_date';
        $this->setName('activity_date');

        $model = new Model_Viewcode();

        $organisationIdentifier = $model->getCode('OrganisationIdentifier', null, '1');
        $activityDateType = $model->getCode('ActivityDateType', null, '1');
        $language = $model->getCode('Language',null,'1');
        $rowSet = $model->getRowsByFields('default_field_values', 'account_id', $account_id);
        $defaultValues = unserialize($rowSet[0]['object']);
        $default = $defaultValues->getDefaultFields();

        $type_array = array();
        $type_array[''] = 'Select anyone';
        foreach($activityDateType[0] as $eachType){
            $type_array[$eachType['id']] = $eachType['Code'];
        }
        //        print_r($type_array);exit();
        /*$this->addElement('select', 'attr_iati_activity_date_type', array(
        'isArray' => true,
        'label' => 'Date Type',
        'MultiOptions' => $type_array,
        'attribs' => array(
        //                                            'disableLoadDefaultDecorators' => true,
        'multiple' => '',

        )
        ));*/

        /* $this->addElement('text', 'attr_iati_activity_date_iso_date', array(
         'isArray' => true,
         'label' => 'Iso Date',
         //'MultiOptions' => $type_array,
         'attribs' => array(
         //'multiple' => '',
         'class' => 'datepicker',
         )
         ));*/

        /* $id = 1;
         $this->addElement('text', "attr_iati_activity_date_iso_date", array(
         'isArray' => true,
         //'setElementsBelongTo' => 'attr_iati_activity_date_type',
         'label' => 'Iso Date',
         'attribs' => array(
         //'multiple' => '',
         'class' => 'datepicker',
         )
         ));*/

        //        foreach()
//        print $state;exit();
        if($state == 'add'){
            $form['attr_iati_activity_date_type1'] = new Zend_Form_Element_Select('attr_iati_activity_date_type');
            $form['attr_iati_activity_date_type1']->setLabel('Date Type')->setIsArray('true')
            ->setAttrib('multiple','')
            ->setRequired()->addMultiOption('', 'Select anyone');
            foreach($activityDateType[0] as $eachActivityDateType){
                $form['attr_iati_activity_date_type1']->addMultiOption($eachActivityDateType['id'], $eachActivityDateType['Code']);
            }

            $form['attr_iati_activity_date_iso_date1'] = new Zend_Form_Element_Text('attr_iati_activity_date_iso_date');
            $form['attr_iati_activity_date_iso_date1']->setLabel('Iso Date')->setIsArray('true');

            $form['iati_activity_date_text1'] = new Zend_Form_Element_Text('iati_activity_date_text');
            $form['iati_activity_date_text1']->setLabel('Date')->setIsArray('true');
             

            $form['activity_id1'] = new Zend_Form_Element_Hidden('activity_id');
            $form['activity_id1']->setValue($activity_id)->setAttrib('class','hidden-field')
            ->setIsArray('true');

            $form['table1'] = new Zend_Form_Element_Hidden('table');
            $form['table1']->setValue('iati_activity_date')
            ->setAttrib('class','hidden-field')->setIsArray('true');

            $form['form_name1'] = new Zend_Form_Element_Hidden('form_name');
            $form['form_name1']->setValue('Activity Date')->setAttrib('class','hidden-field')->setIsArray('true');

            $form['attr_iati_activity_date_xmllang1'] = new Zend_Form_Element_Select('attr_iati_activity_date_xmllang');
            $form['attr_iati_activity_date_xmllang1']->setLabel('Language')->setAttrib('multiple','')
            ->addMultiOption('', 'Select anyone')->setValue($default['language'])->setIsArray('true');
            foreach($language[0] as $eachLanguage){
                $form['attr_iati_activity_date_xmllang1']->addMultiOption($eachLanguage['id'], $eachLanguage['Code']);
            }
            $form['attr_iati_activity_date_xmllang1']
            ->setIsArray('true')->setDescription("<div id= 'remove-date' style='display:none;'>Remove</div>")
            ->setAttrib('multiple','')
            ->setDecorators(array('ViewHelper',
            array('Description',array('escape'=>false,'tag'=>' span')), //escape false because I want html output
            // array('HtmlTag', array('tag' => 'dd')),
            array('Label'),
            ));


            $form['attr_iati_activity_date_type2'] = new Zend_Form_Element_Select('attr_iati_activity_date_type');
            $form['attr_iati_activity_date_type2']->setLabel('Date Type')->setIsArray('true')->setAttrib('multiple','')
            ->setRequired()->addMultiOption('', 'Select anyone');
            foreach($activityDateType[0] as $eachActivityDateType){
                $form['attr_iati_activity_date_type2']->addMultiOption($eachActivityDateType['id'], $eachActivityDateType['Code']);
            }

            $form['attr_iati_activity_date_iso_date2'] = new Zend_Form_Element_Text('attr_iati_activity_date_iso_date');
            $form['attr_iati_activity_date_iso_date2']->setLabel('Iso Date')->setIsArray('true');

            $form['iati_activity_date_text2'] = new Zend_Form_Element_Text('iati_activity_date_text');
            $form['iati_activity_date_text2']->setLabel('Date')->setIsArray('true');
             

            $form['activity_id2'] = new Zend_Form_Element_Hidden('activity_id');
            $form['activity_id2']->setValue($activity_id)->setAttrib('class','hidden-field')
            ->setIsArray('true');

            $form['table2'] = new Zend_Form_Element_Hidden('table');
            $form['table2']->setValue('iati_activity_date')
            ->setAttrib('class','hidden-field')->setIsArray('true');

            $form['form_name2'] = new Zend_Form_Element_Hidden('form_name');
            $form['form_name2']->setValue('Activity Date')->setAttrib('class','hidden-field')->setIsArray('true');

            $form['attr_iati_activity_date_xmllang2'] = new Zend_Form_Element_Select('attr_iati_activity_date_xmllang');
            $form['attr_iati_activity_date_xmllang2']->setLabel('Language')->setAttrib('multiple','')
            ->addMultiOption('', 'Select anyone')->setValue($default['language'])->setIsArray('true');
            foreach($language[0] as $eachLanguage){
                $form['attr_iati_activity_date_xmllang2']->addMultiOption($eachLanguage['id'], $eachLanguage['Code']);
            }
            $form['attr_iati_activity_date_xmllang2']
            ->setIsArray('true')->setDescription("<div id= 'remove-date' style='display:none;'>Remove</div>")
            ->setDecorators(array('ViewHelper',
            array('Description',array('escape'=>false,'tag'=>' span')), //escape false because I want html output
            // array('HtmlTag', array('tag' => 'dd')),
            array('Label'),
            ));

        }
        elseif($state == 'edit'){
            print $state;exit();
            $i = 0;
            foreach($data as $key => $eachData){
               // print $key; //print_r($eachData);
                
               /* print_r($data[$key][$i]);print "<br>";
                $i++;
                */
                $form['attr_iati_activity_date_type'.$i] = new Zend_Form_Element_Select('attr_iati_activity_date_type');
                $form['attr_iati_activity_date_type'.$i]->setLabel('Date Type')->setIsArray('true')
                ->setAttrib('multiple','')->setValue($data['attr_iati_activity_date_type'][$i])
                ->setRequired()->addMultiOption('', 'Select anyone');
                foreach($activityDateType[0] as $eachActivityDateType){
                    $form['attr_iati_activity_date_type'.$i]->addMultiOption($eachActivityDateType['id'], $eachActivityDateType['Code']);
                }

                $form['attr_iati_activity_date_iso_date'.$i] = new Zend_Form_Element_Text('attr_iati_activity_date_iso_date');
                $form['attr_iati_activity_date_iso_date'.$i]->setLabel('Iso Date')
                ->setValue($data['attr_iati_activity_date_iso_date'][$i])->setIsArray('true');

                $form['iati_activity_date_text'.$i] = new Zend_Form_Element_Text('iati_activity_date_text');
                $form['iati_activity_date_text'.$i]->setLabel('Date')
                ->setValue($data['iati_activity_date_text'][$i])->setIsArray('true');
                 

                $form['activity_id'.$i] = new Zend_Form_Element_Hidden('activity_id');
                $form['activity_id'.$i]->setValue($activity_id)->setAttrib('class','hidden-field')
                ->setIsArray('true')->setValue($data['activity_id'][$i]);

                $form['table'.$i] = new Zend_Form_Element_Hidden('table');
                $form['table'.$i]->setValue('iati_activity_date')
                ->setAttrib('class','hidden-field')->setIsArray('true')->setValue($data['table'][$i]);

                $form['form_name'.$i] = new Zend_Form_Element_Hidden('form_name');
                $form['form_name'.$i]->setValue('Activity Date')->setAttrib('class','hidden-field')
                ->setValue($data['form_name'][$i])->setIsArray('true');

                $form['attr_iati_activity_date_xmllang'.$i] = new Zend_Form_Element_Select('attr_iati_activity_date_xmllang');
                $form['attr_iati_activity_date_xmllang'.$i]->setLabel('Language')->setAttrib('multiple','')
                ->addMultiOption('', 'Select anyone')
                ->setValue($data['form_name'][$i])->setIsArray('true');
                foreach($language[0] as $eachLanguage){
                    $form['attr_iati_activity_date_xmllang'.$i]->addMultiOption($eachLanguage['id'], $eachLanguage['Code']);
                }
                $form['attr_iati_activity_date_xmllang'.$i]
                ->setIsArray('true')->setDescription("<div id= 'remove-date' style='display:none;'>Remove</div>")
                ->setAttrib('multiple','')
                ->setDecorators(array('ViewHelper',
                array('Description',array('escape'=>false,'tag'=>' span')), //escape false because I want html output
                // array('HtmlTag', array('tag' => 'dd')),
                array('Label'),
                ));
                $i++;
            }//exit();
        }
//        print_r($form);exit();
        $this->addElements($form);

         
        $save = new Zend_Form_Element_Submit('Save');
        $save->setValue('Save')->setAttrib('class','ajax_save')->setDescription('<div id="add-date" >Add more</div>')
        ->setDecorators(array('ViewHelper',
        array('Description',array('escape'=>false,'tag'=>' span')), //escape false because I want html output
        // array('HtmlTag', array('tag' => 'dd')),
        //array('Label', array('tag' => 'dt', 'class'=>'hidden')),
        ));

        $this->addDisplayGroup(array('attr_iati_activity_date_type',  'attr_iati_activity_date_iso_date',
        'iati_activity_date_text', 'attr_iati_activity_date_xmllang','attr_iati_activity_date_type1'), 'field3');

        $number = $this->getDisplayGroup('field3');
        $number->setDecorators(
        array('FormElements', array('HtmlTag', array('tag'=> 'div', 'id' => 'activity_date_element')),
        array(array('Value'=>'HtmlTag'), array('tag'=>'div','Id'=>'activity-date-wrapper')),
        )
        );
        /*   $form['instance1'] = new Zend_Form_Element_Select('name');
         $form['instance1']->setisArray(true)->setAttrib('multiple','');
         $form['instance2'] = new Zend_Form_Element_Select('name');
         $form['instance2']->setisArray(true)->setAttrib('multiple','');
          
         $this->addElements($form);*/

        /*$this->addDisplayGroup(array('field3',), 'outer');

        $n = $this->getDisplayGroup('outer');
        $n->setDecorators(
        array('FormElements', array('HtmlTag', array('tag'=> 'div', 'class' => 'activity_date_element_outer')),
        array(array('Value'=>'HtmlTag'), array('tag'=>'span','class'=>'value')),
        ));*/
        /* $number->setDescription('<div class="rood">Add more</div>')
         ->setDecorators(array('ViewHelper',
         array('Description',array('escape'=>false,'tag'=>' span')), //escape false because I want html output
         // array('HtmlTag', array('tag' => 'dd')),
         //array('Label', array('tag' => 'dt', 'class'=>'hidden')),
         ));*/
        $this->addElement($save);

        $this->setMethod('post');
    }
}
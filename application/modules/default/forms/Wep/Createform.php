<?php
class Form_Wep_Createform extends Zend_Form
{
    public function init(){
        
    }
    public function create($data)
    {
        $form = array();
//        print_r($data);exit();
        foreach($data as $key=>$eachData){
//            print $key;exit();
//            $form = array();
            //$a = new Zend_Form_Element_Select();
            $element = "Zend_Form_Element_".$eachData['input']."('ASD')";
            $form["$key"] = new $element;
            $form["$key"]->setLabel('ASD');
           /* print $key; 
            print_r($eachData);*/
            
            //print_r($form[$key]);exit();
            //$a[] = $eachData;
        }//exit();
       /* $form['asd'] = new Zend_Form_Element_Text('asd');
        $form['asd']->setLabel('ASD');*/
        $this->addElements($form);
        $this->setMethod('post');
    }
}
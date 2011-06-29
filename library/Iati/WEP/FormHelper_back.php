<?php
class Iati_WEP_FormHelper1
{
    protected $attributes;
    public function formGenerator($class = '', $account_id = '', $activity_id,  $id = '')
    {
        //        print $class; print $account_id; print $activity_id;exit();

        $class = 'Iati_WEP_Activity_'.$class;
        $obj = new $class();
        $obj->setAll( $account_id, $activity_id, $id = '');
        $this->attributes = $obj->getAttributes();
        /*

        }

        public function formGenerator()
        {*/

        $string = "<form name=".$class." action=". $_SERVER['HTTP_HOST']."/iati-aims/public/wep/add-activity-elements/?class=".$class."method='post'>";
        foreach($this->attributes as $key => $eachAttributes){
            //         print_r($eachAttributes);exit();
            $function = $eachAttributes['input'];
            //            print_r($function);exit();
            $string .= call_user_func_array(array($this, $function), array($eachAttributes));
        }
        $string .= "<input type= 'submit' value= 'Submit' />";
        $string .= "</form>";
        return $string;
    }

    public function text($input){
        $string  = "<input type='text'";
        $string .=  "name='".$input['name'];
        if($input['multiple'] == 1){
            $string .=  "[]";
        }
        $string .= "'";
        if($input['value']){
            $string .=  " value='".$input['value'] ."'";
        }
        $string .= "/>";
        //       print $string; exit();
        return $string;

    }
    public function select($input)
    {
        $string = "<select name= '".$input['name']."'>";
        $string .= "<option value=''>Select anyone</option>";
        //        print_r($input['options']);exit();
        foreach($input['options'] as $key => $eachOption){


            $string .= "<option value='".$eachOption['id']."'";
            if($eachOption['id'] == $input['selected']){
                $string .= " selected='" .  $eachOption['id']."'";
            }
            $string .= ">";
            $string .= $eachOption['Code']."</option>";

        }
        $string .= "</select>";
        //        print $string;exit();
        return $string;
    }
    public function hidden($input)
    {
        $string  = "<input type='hidden'";
        $string .=  "name='".$input['name'];
        if($input['multiple'] == 1){
            $string .=  "[]";
        }
        $string .= "'";
        if($input['value']){
            $string .=  " value='".$input['value'] ."'";
        }
        $string .= "/>";
        //       print $string; exit();
        return $string;
    }

    function flatPostArray($post) {
        $postKeys = array();
        $return = array();
        foreach ($post as $key => $value) {
            if (is_array($value)) {
                array_push($postKeys, $key);
            }
        }

        foreach ($post[$postKeys[0]] as $key => $value) {
            $tmp = array();
            foreach ($postKeys as $k => $v) {
                $tmp[$v] = $post[$v][$key];
            }
            array_push($return, $tmp);
        }
        return $return;
    }
}
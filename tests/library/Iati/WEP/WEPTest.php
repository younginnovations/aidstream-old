<?php
class Iati_WEP_WEPTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {

    }

    public function testAccountDefaultField()
    {
        $default = new Iati_WEP_AccountDefaultFieldValues();
        //print_r($default);exit();
        $defaultFields = $default->getDefaultFields();
        $expected = array(
                        'language' => 'en',
                        'currency' => 'USD',
                        'reporting_org' => '',
        );

        $this->assertEquals($expected, $defaultFields);

        $default->setLanguage('fr');
        $default->setCurrency('NPR');
        $default->setReporting_org('UN');

        $defaultFields = $default->getDefaultFields();
        asort($defaultFields);
        $expected = array(
                        'language' => 'fr',
                        'currency' => 'NPR',
                        'reporting_org' => 'UN',
        );
        asort($expected);
        $serialized = serialize($default);
        print_r($serialized);
        $this->assertEquals($expected, $defaultFields);


    }

    public function testAccountDefaultFields()
    {
        $default = new Iati_WEP_AccountDisplayFieldGroup();
        $defaultFields = $default->getProperties();
        $expected = array(
                        'title' => '0',
                        'activity_date' => '0',
                        'participating_organisation' => '0'
                        );

                        $this->assertEquals($expected, $defaultFields);

                        $default->setProperties('title');
                        $default->setProperties('activity_date');

                        $defaultFields = $default->getProperties();
                        asort($defaultFields);
                        $expected = array(
                        'title' => '1',
                        'activity_date' => '1',
                        'participating_organisation' => '0'
                        );
                        asort($expected);
                        print_r($default);
                        $this->assertEquals($expected, $defaultFields);

                        //        throw new Exception('Property tile not found.');
                        $this->setExpectedException("Exception");
                        $default->setProperties('tile');
    }

    public function testGetDefaultFields()
    {
        $default = new Iati_WEP_AccountDisplayFieldGroup();
        $default->setProperties('title');
        $default->setProperties('activity_date');

        print_r($default->getProperties());

        foreach($default->getProperties() as $key => $eachDefault){
            //            print_r($key);
            if($eachDefault == 1){
                $selectedDefaults[]= $key;
            }

        }
        print_r($selectedDefaults);exit();

    }

    /*public function testgetColumns()
    {
        $model = new Model_Wep();
        $a = $model->getColumns('iati_activities');

    }*/

    public function testTitleForm()
    {
        $account_id = 3;
        $title = new Iati_WEP_Activity_Title();
        $titleProperty = $title->getProperties($account_id, '1');

        $data['text'] = '';

        $title->validate($data);
        //        print_r($title);exit();
    }
    
    /*public function testTitles()
    {
        $account_id = 2;
        $title = new Iati_WEP_Activity_Title();
        
        $initial = array();
        $initial['text'] = '';
        $initial['xml_lang'] = '363';
        
        $title->setProperties($initial);
        $title->setAccountAcitivty(array('account_id' => $account_id, 'activity_id'=>'43'));
        $title->setAll();
        
        $objects = array($title);
        $formObj = new Iati_WEP_FormHelper($objects);
        $a = $formObj->getForm();
        print_r($a);
//        $titleProperty = $title->getProperties($account_id, '1');
    }*/
    
    public function testTitles()
    {
        $initial['@currency'] = '363';
        $initial['@xml_lang'] = '363';
        $initial['text'] = '';
        
        $identity = array('account_id'=> '2', 'activity_id'=>'2');
        $classname = 'Iati_WEP_Activity_'. 'Title';
        $globalobj = new $classname();
        $globalobj->setAccountActivity(array('account_id'=>$identity['account_id'], 'activity_id'=>$identity['activity_id']));
        $globalobj->propertySetter($initial);
        
        $initial['@currency'] = '363';
        $initial['@xml_lang'] = '363';
        $initial['text'] = 'obj';
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
            $registryTree->addNode($globalobj);
            $obj =  new $classname();
            $obj->propertySetter($initial);
            $obj->setHtml();
            $registryTree->addNode($obj, $globalobj);
            
            $initial['@currency'] = '363';
        $initial['@xml_lang'] = '363';
        $initial['text'] = 'kkk';
            $obj1 =  new $classname();
            $obj1->propertySetter($initial);
            $obj1->setHtml();
            $registryTree->addNode($obj1, $obj);
                    
            $initial['@currency'] = '363';
        $initial['@xml_lang'] = '363';
        $initial['text'] = 'ooo';
            $obj2 =  new $classname();
            $obj2->propertySetter($initial);
            $obj2->setHtml();
            $registryTree->addNode($obj2, $obj);
            
//            $a = $registryTree->getChildNodes($globalobj);
//            print_r($registryTree->getChildNodes($globalobj));exit;
//            print_r($a);exit;
//            print_r($registryTree->getChildNodes($a));exit;
            $formObj = new Iati_WEP_FormHelper($globalobj);
            $a = $formObj->getForm();
    }

    public function testFormHelper()
    {
        $account_id = '3';
        $activity_id = '1';
        $id = '';
        $class = 'ReportingOrganisation';

        $obj = new Iati_WEP_FormHelper();
        $a = $obj->form($class, $account_id, $activity_id);
        /*$post = array(
                        'activity_id' => '',
                        'text' => '',
                        'xml_lang' => '363',
        );*/
        /*$post = array(
         '0' => array(
         'activity_id' => '1',
         'text' => 'this is title',
         'xml_lang' => '363',
         ),
         '1' => array(
         'activity_id' => '1',
         'text' => 'title title',
         'xml_lang' => '363',
         ),
         );*/
        //        $expected =

        //$obj1 = new Iati_WEP_Activity_Title();
        //        $b = $obj1->validate($post);
        //$obj1->validate($post);
        //        $form = $obj->formGenerator();
        print_r($a);
    }

    public function testActivityDateObject()
    {
        /*$account_id = '3';
         $activity_id = '1';
         $id = '';
         $class = 'Title';

         $obj = new Iati_WEP_FormHelper();
         $a = $obj->formGenerator($class, $account_id, $activity_id);*/
        /*$post = array(
                    'text' => '',
                    'iso_date' => '2011-',
                    'type' => '23',
                    'xml_lang' => '363',
                    'activity_id' => '1',
                );*/
        $post = array(
         '0' =>array(
                    'text' => '',
                    'iso_date' => '02-',
                    'type' => '23',
                    'xml_lang' => '363',
                    'activity_id' => '1',
                ),
         '1' => array(
                    'text' => '',
                    'iso_date' => '2011-02-10',
                    'type' => '',
                    'xml_lang' => '363',
                    'activity_id' => '1',
                )
         );
        //        $expected =

        $obj1 = new Iati_WEP_Activity_ActivityDate();
        //        $b = $obj1->validate($post);
        $error = array();
        $obj1->validate($post, $error);
        print_r($error);
        
        $obj1->setError($error);
        $expected = array(
                '0' => array(
                        'iso_date' => Array(
                            'dateFalseFormat' => "'02-' does not fit the date format 'yyyy-MM-dd'"
                         )
                ),
                '1' => array(
                    'type' => Array(
                    'isEmpty' => "Value is required and can't be empty"
                    )
                ),
            );
        
        //        $form = $obj->formGenerator();
        print_r($a);
        $this->assertEquals($expected, $error);
    }

    public function testNotification()
    {
        $mailObserver = $this->getMock('App_Email', array('send'));

        $registry = Zend_Registry::getInstance();
        $registry->mailer = $mailObserver;
        $mailerParams = array('email'=> 'abhinav@yipl.com.np');
        $toEmail = 'abhinav@yipl.com.np';
        $template = 'user-register';
        $Wep = new App_Notification;
        $Wep->sendemail($mailerParams,$toEmail,$template);
        $actualTokens = $mailObserver->getTokens();
        //var_dump($actualTokens);exit();
        $this->assertEquals($actualTokens, $mailerParams);
        //var_dump($mailObserver);exit();
        $expectedEmail =
"Hi abhinav@yipl.com.np,

Thank you for registering at Iati Web Entry Platform. Your detail has been forwarded to Administrator for approval.
Further details will be forwarded to you after the Administrator approves your request. 
       
-- 
Iati Organization";

        $this->assertEquals($expectedEmail, $mailObserver->getHtml());
    }
    
    public function testAA()
    {
        $model = new Model_Wep();
        $a = $model->getDefaults('default_field_values', 'account_id', '3');
        print_r($a);exit();
    }
    
    public function testTitleValidate()
    {
        
        $rowSet = array(
                    array(
                        'id' => 6,
                        'text' => 'sd',
                        '@xml_lang' => '363',
                        'activity_id' => '2',
                    ),
                    array(
                        'id' => 7,
                        'text' => 'sd',
                        '@xml_lang' => '363',
                        'activity_id' => '2',
                    ),
                );
        $obj = new Iati_WEP_Activity_Title();
        
    }
    
    public function testTransactionFactory()
    {
//        $accountActivity = array('activity_id' => '2', 'account_id'=>'2');
        $model = new Model_Wep();
        $defaultFieldValues = $model->getDefaults('default_field_values',  'account_id', '2');
        $defaults = $defaultFieldValues->getDefaultFields();
        $initial['@currency'] = $defaults['currency'];
        $initial['@xml_lang'] = $defaults['language'];
        $initial['text'] = '';
        
        $activity_id = '2';
        $activity = new Iati_WEP_Activity_Elements_Activity();
        $activity->setAttributes(array('activity_id' => $activity_id));
        
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($activity);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($dbWrapper);

       
        $transactionFactory = new Iati_WEP_Activity_TransactionFactory();
        $tree = $transactionFactory->factory();
//         print_r($tree->xml());exit;
        $formHelper = new Iati_WEP_FormHelper();
        $a = $formHelper->getForm();
        
        print_r($a);exit;
        /*$registryTree = Iati_WEP_TreeRegistry::getInstance();
        $childNodes = $registryTree->getChildNodes($globalobj);*/
//        print_r($tree->xml());exit;
    }  
    
    
    public function testFlatPost()
    {
        
        $array1 = array(
//            '0'=> array(
                'Title_id' => array(
                    '0' => '1',
                    '1'=>'2', 
                ),
                'Title_text' => array(
                    '0' => 'title1',
                    '1' => 'title2',
                ),
//            ),
            
        );
        /*$array = array(
            'activity_id' => array(
                '0' => array('0' => '1'),
            ),
            
            'TransactionType_text' => array(
                '0' => array('1' => 'sdfsdf', 
                    '2'=> 'sdfsss'),
                '3' => array('3' => 'ss', 
                    '5'=> 's'),
            ),
            'TransactionType_id' => array(
                '0' => array('0' => '88'),
                '3' => array('0' => '7'),
            ),
            'Provider_Org' => array(
                '0' => array(
                    '0'=> array('0' => 'asfasdf'),
                    '1' => array('1' => 'jsdg')
                ),
            ),
        );*/
        
        $array = array(
    'Activity_activity_id' => '2',
    'Transaction_transaction_id' => array(
        '0' => '2',
        '1' => '5'
    ),
    'TransactionType_name' => array(
        '0' => array(
            '1' => 'ab', '2' => 'cd', '3' => 'ef'
        ),
        '1' => array(
            '1' => 'abc', '2' => 'cde', '3' => 'efg'
        )
    ),
    'TransactionType_code' => array(
        '0' => array(
            '1' => 'abb', '2' => 'cdd', '3' => 'eff'
        ),
        '1' => array(
            '1' => 'abbb', '2' => 'cdd77d', //'3' => 'efff'
        )
    ),
    'ProviderInfo_name' => array(
        '0' => array(
            '1' => 'ab', '2' => 'cd', '3' => 'ef'
        ),
        '1' => array(
            '1' => 'abc', '2' => 'cde', '3' => 'efg'
        )
    ),
    'ProviderInfo_code' => array(
        '0' => array(
            '1' => 'abb', '2' => 'cdd', '3' => 'eff'
        ),
        '1' => array(
            '1' => 'abbb', '2' => 'cddd', '3' => 'efff'
        )
    ),
    'ThirdLevel_subject' => array(
        '0' => array(
            '1' => array('0' => 'xxx'),
            '2' => array('0' => '0xxx'),
            '3' => array('0' => 'xxx')
        ),
        '1' => array(
            '1' => array('0' => 'yyy'),
            '2' => array('0' => 'y'),
            '3' => array('0' => 'yyy')
        )
    ),
    'submit' => 'Go'
);
        
//        $a = $this->getFields('TransactionType', $array);
//        print_r($a);exit;[0] => Array
                       
        $b = $this->flatArray($array);
        print_r($b);exit;
    }
    
    /*public function getFields($class, $array)
    {
        $newArray = array();
        
        foreach($array as $key => $value){
            $key_array = explode('_', $key);
            if($key_array[0] == $class){
                array_shift($key_array);
                $newArray[implode("", $key_array)] = $value;
            }
        }
        return $newArray;
    }    
    
    public function flatArray($array)
    {
        print_r($array);testFlatPost
        $array_values = array_values($array);
        $returnArray = array();
//        print_r($array_values);exit;
        foreach(array_keys($array_values[0]) as $i ){
            foreach($array as $key => $val){
                $returnArray[$key][$i] = $val[$i];
            }
        }
//        print_r($returnArray);
        
        $finalArray = array();
        foreach($returnArray as $key => $value){
            foreach($value as $k => $v){
                $finalArray[$k][$key] = $v;
            }
        }
        
        print_r($finalArray);exit;
//        return $returnArray;
    }*/
    

function flatArray ($array) {
    $result = array();
    
    foreach ($array as $key => $val) {
        array_push($result, $this->recurArray($key, $val, array()));
    }
    
//    print_r($result);
    
    $result_depths = array();
    foreach($result as $array) {
        $depth = (is_array($array)) ? $this->array_depth($array) : 1;
        array_push($result_depths, $depth);
    }
    
    $max_depth = max($result_depths);
    
    $final = $this->combineAll($result, $max_depth);
    
//    print_r($final);
    
    //print_r($final['0']);
    foreach($final as $key => $val) {
        if (!is_array($val)) {
            continue;
        }
        
        $result_depths = array();
        foreach($final[$key] as $array) {
            $depth = (is_array($array)) ? $this->array_depth($array) : 1;
            array_push($result_depths, $depth);
        }
        $max_depth = max($result_depths);
        $final[$key] = $this->combineAll($final[$key], $max_depth);
    }
    
    
    return $final;
//    print_r($final);
}

function combineAll($array, $max_depth=4, $depth=1, $result=array()) {
    $process = array();
    foreach($array as $k => $a) {
        if (is_array($a)) {
            if ($this->array_depth($a) == $depth) {
                array_push($process, $a);
            }
        }
        else {
            $result[$k] = $a;
        }
    }
    
    if ($depth > $max_depth) {
        return $result;
    }
    
    while (!empty($process)) {
        $arr = array_shift($process);
        
        foreach ($arr as $key => $val) {
            if (isset($result[$key]) && is_array($result[$key])) {
                //print_r($result[$key]);
                
                if (sizeof($val) < 2) {
                    list($k, $v) = each($val);
                    $result[$key][$k] = $v;
                }
                else {
                    array_push($result[$key], $val);
                }
            }
            else {
                $result[$key] = $val;
            }
        }
    }
    
    return $this->combineAll($array, $max_depth, ++$depth, $result);
}

/**
 * Actual recursion happens here
 *
 */
function recurArray ($key, $arr, $array) {
    
    if (is_array($arr)) {
        foreach ($arr as $k => $v) {
            $array[$k] = $this->recurArray($key, $v, array());
        }
    }
    else {
        return array($key => $arr);
    }
    
    return $array;
}

/**
 *
 * http://stackoverflow.com/questions/262891/
 *    is-there-a-way-to-find-how-how-deep-a-php-array-is
 */
function array_depth ($array) {
    $max_depth = 1;
    
    foreach ($array as $value) {
        if (is_array($value)) {
            $depth = $this->array_depth($value) + 1;
            
            if ($depth > $max_depth) {
                $max_depth = $depth;
            }
        }
    }
    return $max_depth;
}

public function testExplode()
{
    $a = 'parent0';
    $b = explode('item', $a);
    print_r($b);
}
}   

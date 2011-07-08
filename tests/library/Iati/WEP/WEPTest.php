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
    
    public function testTitles()
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
}

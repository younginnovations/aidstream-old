<?php
class Iati_Activity_Element_ActivityDate extends Iati_Activity_Element
{

    protected $_type = 'ActivityDate';
    protected $_parentType = 'Activity';
    protected $_validAttribs = array('text' => '', '@type' => '',
                                     '@iso_date' => '', '@xml_lang' => '' );
}

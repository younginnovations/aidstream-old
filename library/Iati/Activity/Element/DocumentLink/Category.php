<?php
class Iati_Activity_Element_DocumentLink_Category extends Iati_Activity_Element
{
    protected $_type = 'Category';
    protected $_parentType = 'DocumentLink';
    protected $_validAttribs = array('text' => '', '@code' => '', '@xml_lang' => '');
}

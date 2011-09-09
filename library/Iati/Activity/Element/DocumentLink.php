<?php
class Iati_Activity_Element_DocumentLink extends Iati_Activity_Element
{
    protected $_type = 'DocumentLink';
    protected $_parentType = 'Activity';
    protected $_validAttribs = array('@url' => '');
}

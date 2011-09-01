<?php
class Iati_Activity_Element_Transaction_DisbursementChannel extends Iati_Activity_Element
{
    protected $_type = 'DisbursementChannel';
    protected $_parentType = 'Transaction';
    protected $_validAttribs = array('text' => '', '@code' => '');
}

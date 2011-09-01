<?php
class Iati_Activity_Element_Transaction_TransactionDate extends Iati_Activity_Element
{
    protected $_type = 'TransactionDate';
    protected $_parentType = 'Transaction';
    
    protected $_validAttribs = array('text' => '', '@iso_date' => '');
}

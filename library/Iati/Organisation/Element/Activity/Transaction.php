<?php

class Iati_Organisation_Element_Activity_Transaction extends Iati_Organisation_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Transaction';
    protected $displayName = 'Transaction';
    protected $attribs = array('id' , '@ref');
    protected $iatiAttribs = array('@ref');
    protected $childElements = array(
                                        'TransactionType',
                                        'ProviderOrganisation' ,
                                        'ReceiverOrganisation' ,
                                        'Value',
                                        'Description' ,
                                        'TransactionDate',
                                        'FlowType' ,
                                        'FinanceType' ,                                        
                                        'AidType' ,          
                                        'DisbursementChannel' ,
                                        'TiedStatus',
                                     );
    protected $tableName = 'iati_transaction';
}
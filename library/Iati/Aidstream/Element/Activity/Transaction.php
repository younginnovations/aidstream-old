<?php

class Iati_Aidstream_Element_Activity_Transaction extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Transaction';
    protected $displayName = 'Transaction';
    protected $attribs = array('id' , '@ref');
    protected $iatiAttribs = array('@ref');
    protected $childElements = array(
                                        'TransactionType',
                                        'ProviderOrg' ,
                                        'ReceiverOrg' ,
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
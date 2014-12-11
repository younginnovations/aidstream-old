<?php

class Iati_Aidstream_Element_Activity_Transaction extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Transaction';
    protected $displayName = 'Transaction';
    protected $attribs = array('id', '@ref');
    protected $iatiAttribs = array('@ref');
    protected $childElements = array(
                                        'TransactionType',
                                        'TransactionDate',
                                        'Value',
                                        'Description' ,
                                        'ProviderOrg' ,
                                        'ReceiverOrg' ,
                                        'DisbursementChannel' ,
                                        'Sector',
                                        'RecipientCountry',
                                        'RecipientRegion',
                                        'FlowType' ,
                                        'FinanceType' ,                                        
                                        'AidType' ,          
                                        'TiedStatus'
                                    );
    protected $tableName = 'iati_transaction';
}
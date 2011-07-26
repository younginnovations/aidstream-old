<?php
class Iati_WEP_TableClassMapper
{
    protected $classMapper = array(
            'Title' => 'iati_title',
            'Activity_Date' => 'iati_activity_date',
            'ReportingOrganisation' => 'iati_reporting_org',
            'IatiIdentifier' => 'iati_identifier',
            'Transaction' => 'iati_transaction',
            'Transaction_TransactionType' => 'iati_transaction/transaction_type',
            'Transaction_ProviderOrg' => 'iati_transaction/provider_org',
			'Activity' => 'iati_activity',    
            
    );
    
    public function getTableName($classname)
    {
        $strippedClassName = str_replace('Iati_WEP_Activity_Elements_', "", $classname);
        if(isset($this->classMapper[$strippedClassName]))
            return $this->classMapper[$strippedClassName];
        else
            return null;
    }
    
    
}
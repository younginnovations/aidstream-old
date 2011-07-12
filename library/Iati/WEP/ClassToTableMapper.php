<?php
class Iati_WEP_ClassToTableMapper 
{
    protected $classMapper = array(
            'Title' => 'iati_title',
            'Activity_Date' => 'iati_activity_date',
            'ReportingOrganisation' => 'iati_reporting_org',
            'IatiIdentifier' => 'iati_identifier',
            
    );
    
    public function getTableName($object)
    {
        $classname = get_class($object);
        $strippedClassName = str_replace('Iati_WEP_Activity_', "", $classname);
        return $this->classMapper[$strippedClassName];
    }
    
    
}
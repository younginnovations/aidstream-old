<?php
/**
 * Class to fetch codelist data from the database.
 *
 * @author bhabishyat <bhabishyat@gmail.com>
 */
class Iati_Core_Codelist 
{
    public static function getCodelistTable($elementName , $attributeName)
    {
        if($attributeName == '@xml_lang'){
            return 'Language';
        } else if($attributeName == '@currency' || $attributeName == 'currency'){
            return 'Currency';
        } else {
            $switch = $elementName."_".preg_replace('/@/','',$attributeName);
            switch($switch)
            {   
                case "Indicator_measure":
                    $return = 'IndicatorMeasure';
                    break;
                case "Activity_default_currency":
                    $return = 'Currency';
                    break;
                
                case "Language_text":
                    $return = 'Language';
                    break;
                    
                case "TransactionType_code":
                    $return = 'TransactionType';
                    break;
                    
                case "TransactionValue_currency":
                    $return = 'Currency';
                    break;
                
                case "FlowType_code":
                    $return = 'FlowType';
                    break;
                
                case "FinanceType_code":
                    $return = 'FinanceType';
                    break;
                
                case "AidType_code":
                    $return = 'AidType';
                    break;
                
                case "DisbursementChannel_code":
                    $return = 'DisbursementChannel';
                    break;
                
                case "TiedStatus_code":
                    $return = 'TiedStatus';
                    break;
                
                case "Condition_type":
                    $return = 'ConditionType';
                    break;
                
                case "Category_code":
                    $return = 'DocumentCategory';
                    break;
                
                case "Result_type":
                    $return = 'ResultType';
                    break;
                
                case "ReportingOrg_type":
                    $return = 'OrganisationType';
                    break;
                
                case "ActivityStatus_code":
                    $return = 'ActivityStatus';
                    break;
                
                case "ActivityScope_code":
                    $return = 'ActivityScope';
                    break;
                
                case "ActivityDate_type":
                    $return = 'ActivityDateType';
                    break;
                
                case "ParticipatingOrg_type":
                    $return = 'OrganisationType';
                    break;
                
                case "ParticipatingOrg_role":
                    $return = 'OrganisationRole';
                    break;
                
                case "RecipientCountry_code":
                    $return = 'Country';
                    break;
                
                case "RecipientRegion_code":
                    $return = 'Region';
                    break;
                
                case "RecipientRegion_vocabulary":
                    $return = 'RegionVocabulary';
                    break;
                
                case "Sector_vocabulary":
                    $return = 'Vocabulary';
                    break;
                
                case "Sector_code":
                    $return = 'Sector';
                    break;
                
                case "PolicyMarker_significance":
                    $return = 'PolicySignificance';
                    break;
                
                case "PolicyMarker_code":
                    $return = 'PolicyMarker';
                    break;
                
                case "PolicyMarker_vocabulary":
                    $return = 'Vocabulary';
                    break;
                
                case "DefaultFlowType_code":
                    $return = 'FlowType';
                    break;
                
                case "DefaultFinanceType_code":
                    $return = 'FinanceType';
                    break;
                
                case "DefaultAidType_code":
                    $return = 'AidType';
                    break;
                
                case "DefaultTiedStatus_code":
                    $return = 'TiedStatus';
                    break;
                
                case "RelatedActivity_type":
                    $return = 'RelatedActivityType';
                    break;
            
                case "CollaborationType_code":
                    $return = 'CollaborationType';
                    break;
                    
                case "Description_type":
                    $return = 'DescriptionType';
                    break;
                
                case "DocumentLink_format":
                    $return = 'FileFormat';
                    break;
                    
                case "LocationType_code":
                    $return = 'LocationType';
                    break;
                    
                case "Administrative_country":
                    $return = 'Country';
                    break;
                    
                case "Coordinates_precision":
                    $return = 'PercisionCode';
                    break;
                
                case "GazetteerEntry_gazetteer_ref":
                    $return = 'GazetteerAgency';
                    break;
                case "Budget_type":
                    $return = 'BudgetType';
                    break;
                case "CountryBudgetItems_vocabulary":
                    $return = 'BudgetIdentifierVocabulary';
                    break;
                case "BudgetItem_code":
                    $return = 'BudgetIdentifier';
                    break;
                default:
                    $return = false;
                    break;    
            }
        }
        return $return;
    }
    
    
    public static function getFieldById($tablename , $id , $getField = 'Code')
    {
        $db = new Zend_Db_Table($tablename);
        $rowSet = $db->select()->where("id =?", $id);
        
        $result = $db->fetchRow($rowSet);
        if($result){
            $result = $result->toArray();
        }
        if($getField){
            return ($result[$getField])?$result[$getField]:$result['Code'];
        } else {
            return $result['Code'];
        }
    }
    
    public static function getCodeByAttrib($elementName , $attributeName , $id , $field = 'Code')
    {
        $tableName = self::getCodelistTable($elementName , $attributeName);
        if($tableName){
            return self::getFieldById($tableName , $id , $field);
        } else {
            return $id;
        }
    }
}
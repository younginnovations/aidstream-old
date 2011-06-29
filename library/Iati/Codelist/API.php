<?php

//include_once(APPLICATION_PATH."/../library/App/Google_Spreadsheet.php");

class Iati_Codelist_API
{

    protected $codelistName;
    protected $language = 'en';
    
    protected $languageMapper = array(
                                    'en' => '1',
                                    'fr' => '2',
                                    'sp' => '3',
                                );

    public function getCodelistName()
    {
        return $this->codelistName;
    }

    public function getLang()
    {
        return $this->language;
    }


    public function __construct($codelistName = null, $lang = 'en'){
        $this->codelistName = $codelistName;
       
        $this->language = $this->languageMapper[$lang];
     
    }

    public function getCodelistCollection()
    {   
       
    if(empty($this->language)){
            return array('error'=> array('Invalid Language.'));
        }
        if($this->codelistName){
        try{
            switch ($this->codelistName){
                case Iati_Codelist_Constants::$ActivityDateType:
                    $collection = new Iati_Codelist_Collection_ActivityDateType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$ActivityStatus:
                    $collection = new Iati_Codelist_Collection_ActivityStatus($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$AdministrativeArea1:
                    $collection = new Iati_Codelist_Collection_AdministrativeArea1($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$AdministrativeArea2:
                    $collection = new Iati_Codelist_Collection_AdministrativeArea2($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$AidType:
                    $collection = new Iati_Codelist_Collection_AidType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$BilateralAidAgency:
                    $collection = new Iati_Codelist_Collection_BilateralAidAgency($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$BudgetType:
                    $collection = new Iati_Codelist_Collection_BudgetType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$CollaborationType:
                    $collection = new Iati_Codelist_Collection_CollaborationType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$ConditionType:
                    $collection = new Iati_Codelist_Collection_ConditionType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$Country:
                    $collection = new Iati_Codelist_Collection_Country($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$Currency:
                    $collection = new Iati_Codelist_Collection_Currency($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$DescriptionType:
                    $collection = new Iati_Codelist_Collection_DescriptionType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$DisbursementChannel:
                    $collection = new Iati_Codelist_Collection_DisbursementChannel($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$Document:
                    $collection = new Iati_Codelist_Collection_Document($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$FileFormat:
                    $collection = new Iati_Codelist_Collection_FileFormat($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$FinanceType:
                    $collection = new Iati_Codelist_Collection_FinanceType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$FlowType:
                    $collection = new Iati_Codelist_Collection_FlowType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$GazetteerAgency:
                    $collection = new Iati_Codelist_Collection_GazetteerAgency($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$GeographicalPrecision:
                    $collection = new Iati_Codelist_Collection_GeographicalPrecision($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$IndicatorMeasure:
                    $collection = new Iati_Codelist_Collection_IndicatorMeasure($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$Language:
                    $collection = new Iati_Codelist_Collection_Language($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$LocationType:
                    $collection = new Iati_Codelist_Collection_LocationType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$OrganisationIdentifierIngo:
                    $collection = new Iati_Codelist_Collection_OrganisationIdentifierIngo($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$OrganisationIdentifierMultilateral:
                    $collection = new Iati_Codelist_Collection_OrganisationIdentifierMultilateral($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$OrganisationRole:
                    $collection = new Iati_Codelist_Collection_OrganisationRole($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$OrganisationType:
                    $collection = new Iati_Codelist_Collection_OrganisationType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$PolicyMarker:
                    $collection = new Iati_Codelist_Collection_PolicyMarker($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$PolicySignificance:
                    $collection = new Iati_Codelist_Collection_PolicySignificance($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$PublisherType:
                    $collection = new Iati_Codelist_Collection_PublisherType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$Region:
                    $collection = new Iati_Codelist_Collection_Region($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$RelatedActivityType:
                    $collection = new Iati_Codelist_Collection_RelatedActivityType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$ResultType:
                    $collection = new Iati_Codelist_Collection_ResultType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$Sector:
                    $collection = new Iati_Codelist_Collection_Sector($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$TiedStatus:
                    $collection = new Iati_Codelist_Collection_TiedStatus($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$TransactionType:
                    $collection = new Iati_Codelist_Collection_TransactionType($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$VerificationStatus:
                    $collection = new Iati_Codelist_Collection_VerificationStatus($this->language);
                    return $collection->getResult();
                    break;
                case Iati_Codelist_Constants::$Vocabulary:
                    $collection = new Iati_Codelist_Collection_Vocabulary($this->language);
                    return $collection->getResult();
                    break;
                    
                default:
                    return array('error' => array('Invalid codelist name'));
                    break;
            }
        }
        catch(Exception $e){
            print_r($e->getMessage());exit();
            /*$result['codelist']['error'] = array($e->getMessage());
             return $result;*/
        }
        }
        else{
            $collection = new Iati_Codelist_Collection_CodeLists($this->language);
            return $collection->getResult();
        }
    }


}
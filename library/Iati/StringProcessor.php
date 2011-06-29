<?php
class Iati_StringProcessor
{
    protected $version;
    protected $generatedDateTime;
    protected $fileString;
    protected $activitiesObject;

    public function setActivityObject($obj){
        $this->activitiesObject = $obj;
    }
    public function getActivityObject(){
        return $this->activitiesObject;
    }
    
    public function __construct($string){
        $this->fileString = $string;
        $mainArray = $this->stringToObject();
        $this->objectMapper($mainArray);
//        $iatiObject[] = $this->getActivityObject();
//        array_unshift($iatiObject, array('version'=>$this->getVersion()), array('generated-datetime' => $this->getGeneratedDateTime()));
//        print_r($iatiObject);exit();
//        return $iatiObject;
    }

    public function setVersion($version){
        $this->version = $version;
    }
    
    public function getVersion()
    {
        return $this->version;
    }
    public function setGeneratedDateTime($generatedDateTime)
    {
        $this->generatedDateTime = $generatedDateTime;
    }
    
    public function getGeneratedDateTime()
    {
        return $this->generatedDateTime;
    }
    public function stringToObject()
    {
        $xmlObject = new SimpleXMLElement($this->fileString);
        return $xmlObject;
    }

    public function objectMapper($xmlObject)
    {
        $attr = $xmlObject->attributes();
        $this->setVersion($attr->{'version'});
        $this->setGeneratedDateTime($attr->{'generated-datetime'});
        foreach($xmlObject as $activity){
            $activityObject = new Iati_Activity();
            $attr = $activity->attributes();
            $activityObject->setIati_activity(Iati_Activity_IatiActivity::Process($attr));
            $activityObject->setIati_identifier(Iati_Activity_IatiIdentifier::Process($activity->xpath('iati-identifier')));
            $activityObject->setReporting_org(Iati_Activity_ReportingOrg::Process($activity->xpath('reporting-org')));
            $activityObject->setParticipating_org(Iati_Activity_ParticipatingOrg::Process($activity->xpath('participating-org')));
            $activityObject->setRecipient_country(Iati_Activity_RecipientCountry::Process($activity->xpath('recipient-country')));
            $activityObject->setRecipient_region(Iati_Activity_RecipientRegion::Process($activity->xpath('recipient-region')));
            $activityObject->setTitle(Iati_Activity_Title::Process($activity->xpath('title')));
            $activityObject->setDescription(Iati_Activity_Description::Process($activity->xpath('description')));
            $activityObject->setActivity_website(Iati_Activity_ActivityWebsite::Process($activity->xpath('activity-website')));
            $activityObject->setActivity_date(Iati_Activity_ActivityDate::Process($activity->xpath('activity-date')));
            $activityObject->setOther_identifier(Iati_Activity_OtherIdentifier::Process($activity->xpath('other-identifier')));
            $activityObject->setRelated_activity(Iati_Activity_RelatedActivity::Process($activity->xpath('related-activity')));
            $activityObject->setSector(Iati_Activity_Sector::Process($activity->xpath('sector')));
            $activityObject->setPolicy_marker(Iati_Activity_PolicyMarker::Process($activity->xpath('policy-marker')));
            $activityObject->setCollaboration_type(Iati_Activity_CollaborationType::Process($activity->xpath('collaboration-type')));
            $activityObject->setDefault_flow_type(Iati_Activity_DefaultFlowType::Process($activity->xpath('default-flow-type')));
            $activityObject->setDefault_aid_type(Iati_Activity_DefaultAidType::Process($activity->xpath('default-aid-type')));
            $activityObject->setDefault_finance_type(Iati_Activity_DefaultFinanceType::Process($activity->xpath('default-finance-type')));
            $activityObject->setDefault_tied_status(Iati_Activity_DefaultTiedStatus::Process($activity->xpath('default-tied-status')));
            $activityObject->setActivity_status(Iati_Activity_ActivityStatus::Process($activity->xpath('activity-status')));
            $activityObject->setContact_info(Iati_Activity_ContactInfo::Process($activity->xpath('contact-info')));
            $activityObject->setTransaction(Iati_Activity_Transaction::Process($activity->xpath('transaction')));
            $activityObject->setDocument_link(Iati_Activity_DocumentLink::Process($activity->xpath('document-link')));
             
//            print_r($activityObject);exit();
            $activities[] = $activityObject;
             
            /*foreach($activity as $key => $elements){
             print_r($elements);exit();
             }*/
        }
        $this->setActivityObject($activities);
    }
    

}
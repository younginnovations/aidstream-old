<?php
class Iati_Activity_Element_Activity extends Iati_Activity_Element
{
    protected $_parentTypeName = 'ActivityCollection';
    protected $_validAttribs   = array(
        'xmlLang',
        'defaultCurrency',
        'hierarchy',
        'lastUpdatedDatetime',
    );
    protected $_validElements  = array(
        'iatiIdentifier',
        'reportingOrg',
        'participatingOrg',
        'recipientCountry',
        'recipientRegion',
        'title',
        'description',
        'activityWebsite',
        'activityDate',
        'otherIdentifier',
        'relatedActivity',
        'sector',
        'policyMarker',
        'collaborationType',
        'defaultFlowType',
        'defaultAidType',
        'defaultFinanceType',
        'defaultTiedStatus',
        'activityStatus',
        'contactInfo',
        'budget',
        'plannedDisbursement',
        'conditions',
        'transaction',
        'documentLink',
        'location',
    );
}

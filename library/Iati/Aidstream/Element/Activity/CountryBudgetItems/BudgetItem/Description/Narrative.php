<?php
class Iati_Aidstream_Element_Activity_CountryBudgetItems_BudgetItem_Description_Narrative extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $isRequired = true;
    protected $className = 'Narrative';
    protected $displayName = 'Narrative';
    protected $tableName = 'iati_country_budget_items/budget_item/description/narrative';
    protected $attribs = array('id', '@xml_lang', 'text');
    protected $iatiAttribs = array('@xml_lang', 'text');
    protected $viewScriptEnabled = true;
}
<?php
class Iati_Activity_Transaction
{

    private $transactionType = "";
    private $providerOrg = "";
    private $recevierOrg = "";
    private $value = "";
    private $description = "";
    private $transactionDate = "";
    private $flowType = "";
    private $aidType = "";
    private $financeType = "";
    private $tiedStatus = "";
    private $disbursementChannel = "";

    public function setTransactionType($value)
    {
        $this->transactionType = $value;
    }

    public function setProviderOrg($value)
    {
        $this->providerOrg = $value;
    }
    public function setRecevierOrg($value)
    {
        $this->recevierOrg = $value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
    public function setDescription($value)
    {
        $this->description = $value;
    }
    public function setTransactionDate($value)
    {
        $this->transactionDate = $value;
    }
    public function setFlowType($value)
    {
        $this->flowType = $value;
    }
    public function setAidType($value)
    {
        $this->aidType = $value;
    }
    public function setFinanceType($value)
    {
        $this->financeType = $value;
    }
    public function setTiedStatus($value)
    {
        $this->tiedStatus = $value;
    }
    public function setDisbursementChannel($value)
    {
        $this->disbursementChannel = $value;
    }

    public function getTransactionType() {
        return $this->transactionType;
    }

    public function getProviderOrg() {
        return $this->providerOrg;
    }

    public function getRecevierOrg() {
        return $this->recevierOrg;
    }

    public function getValue() {
        return $this->value;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getTransactionDate() {
        return $this->transactionDate;
    }

    public function getFlowType() {
        return $this->flowType;
    }

    public function getAidType() {
        return $this->aidType;
    }

    public function getFinanceType() {
        return $this->financeType;
    }

    public function getTiedStatus() {
        return $this->tiedStatus;
    }

    public function getDisbursementChannel() {
        return $this->disbursementChannel;
    }

        public static function Process($xmlObject)
    {
        //        print_r($xmlObject);exit();
        foreach($xmlObject as $element)
        {
            $transaction = new Iati_Activity_Transaction();

            /*$namespaces = $element->getNameSpaces(true);
             if($namespaces){
             $xm = $element->children($namespaces['xml']);
             $xm = (array)$xm;
             if($xm['@attributes']['lang']){
             $contactInfo->setXmlLang($xm['@attributes']['lang']);
             }
             }*/
            /* $transactionTypeArray = array();
             foreach($element->xpath('transaction-type') as $value){

             $attr = $value->attributes();
             $transaction_type = new stdClass();
             $transaction_type->code = (string)$attr->{'code'};
             $transaction_type->text = (string)$value;
             $transactionTypeArray[] = $transaction_type;
             }
             $transaction->setTransactionType($transactionTypeArray);*/

            foreach($element->xpath('transaction-type') as $value){
                $attr = $value->attributes();
                $transaction->transactionType->code = (string)$attr->{'code'};
                $transaction->transactionType->text = (string)$value;
            }
            //            print_r($transaction);exit();
            foreach($element->xpath('provider-org') as $value){
                $attr = $value->attributes();
                $transaction->providerOrg->ref = (string)$attr->{'ref'};
                $transaction->providerOrg->{'provider-activity-id'} = (string)$attr->{'provider-activity-id'};
                $transaction->providerOrg->text = (string)$value;
            }
            //            print_r($transaction);exit();
            foreach($element->xpath('receiver-org') as $value){
                $attr = $value->attributes();
                $transaction->recevierOrg->ref = (string)$attr->{'ref'};
                $transaction->recevierOrg->{'receiver-activity-id'} = (string)$attr->{'receiver-activity-id'};
                $transaction->recevierOrg->text = (string)$value;
            }
            foreach($element->xpath('value') as $value){
                $attr = $value->attributes();
                $transaction->value->currency = (string)$attr->{'currency'};
                $transaction->value->{'value-date'} = (string)$attr->{'value-date'};
                $transaction->value->text = (string)$value;
            }
            foreach($element->xpath('description') as $value){
                $namespaces = $value->getNameSpaces(true);
                if($namespaces){
                    $xm = $value->children($namespaces['xml']);
                    $xm = (array)$xm;
                    if($xm['@attributes']['lang']){
                        $transaction->description->{'xml:lang'} = ($xm['@attributes']['lang']);
                    }
                }

                $transaction->description->text = (string)$value;
            }
            foreach($element->xpath('transaction-date') as $value){
                $attr = $value->attributes();
                $transaction->transactionDate->{'iso-date'} = (string)$attr->{'iso-date'};
                $transaction->transactionDate->text = (string)$value;
            }
            foreach($element->xpath('flow-type') as $value){
                $attr = $value->attributes();
                $namespaces = $value->getNameSpaces(true);
                if($namespaces){
                    $xm = $value->children($namespaces['xml']);
                    $xm = (array)$xm;
                    if($xm['@attributes']['lang']){
                        $transaction->flowType->{'xml:lang'} = ($xm['@attributes']['lang']);
                    }
                }
                $transaction->flowType->code = (string)$attr->{'code'};
                $transaction->flowType->text = (string)$value;
            }
            foreach($element->xpath('aid-type') as $value){
                $attr = $value->attributes();
                $namespaces = $value->getNameSpaces(true);
                if($namespaces){
                    $xm = $value->children($namespaces['xml']);
                    $xm = (array)$xm;
                    if($xm['@attributes']['lang']){
                        $transaction->aidType->{'xml:lang'} = ($xm['@attributes']['lang']);
                    }
                }
                $transaction->aidType->code = (string)$attr->{'code'};
                $transaction->aidType->text = (string)$value;
            }
            foreach($element->xpath('finance-type') as $value){
                $attr = $value->attributes();
                $namespaces = $value->getNameSpaces(true);
                if($namespaces){
                    $xm = $value->children($namespaces['xml']);
                    $xm = (array)$xm;
                    if($xm['@attributes']['lang']){
                        $transaction->financeType->{'xml:lang'} = ($xm['@attributes']['lang']);
                    }
                }
                $transaction->financeType->code = (string)$attr->{'code'};
                $transaction->financeType->text = (string)$value;
            }
            foreach($element->xpath('tied-status') as $value){
                $attr = $value->attributes();
                $namespaces = $value->getNameSpaces(true);
                if($namespaces){
                    $xm = $value->children($namespaces['xml']);
                    $xm = (array)$xm;
                    if($xm['@attributes']['lang']){
                        $transaction->tiedStatus->{'xml:lang'} = ($xm['@attributes']['lang']);
                    }
                }
                $transaction->tiedStatus->code = (string)$attr->{'code'};
                $transaction->tiedStatus->text = (string)$value;
            }
            foreach($element->xpath('disbursement-channel') as $value){
                $attr = $value->attributes();
                
                $transaction->disbursementChannel->code = (string)$attr->{'code'};
                $transaction->disbursementChannel->text = (string)$value;
            }
            $transactionArray[] = $transaction;
        }
        return $transactionArray;
    }

}
<?php
    include_once("Config.php");
    include_once( BASE_PATH.'/Lib/ExchangeRate.php');
    include_once( BASE_PATH. '/Lib/AidstreamConnector.php');
    $exchange = new ExchangeRate();
    $aidConnector = new AidstreamConnector();

    define('DEFAULT_CURRENCY' , 'USD');
    define('DEFAULT_ORG' , 'All');
    $filename = INPUT_FILE_DIR . COMBINED_ORG_FILENAME;
    $outputDir = INPUT_JSON_DIR;

    exec('rm -r '.$outputDir.'*.json');
    if(!file_exists($outputDir)) { mkdir($outputDir); }

    $fp = fopen($filename , 'r');
    $reportingOrg = array();
    $title =  fgetcsv($fp);
    $title = preg_replace('/-/' , '_' , $title);
    $keys = array_flip($title);

    $outputData = array();
    while (($data = fgetcsv($fp)) !== FALSE) {
        foreach($keys as $key=>$keyValue){
            $out[$key] = $data[$keyValue];
        }
        // Organisation Name
        $reportingOrgName = $out['reporting_organisation'];

        // Activity Status
        $activityStatus = array('Completion' , 'Post-completion' , 'Implementation' , 'Pipeline/identification' , 'Cancelled');
        foreach($activityStatus as $element){
            $statusCode = $out['activity_status_code'];
            $status = $aidConnector->getNames('activity_status' , $statusCode);
            if($status != $element) continue;
            $key = preg_replace('/(\-|\/)/' , '_' , strtolower($element));
            
            $outputData[DEFAULT_ORG]['activity_status'][$key]['count']  += 1;
            $outputData[$reportingOrgName]['activity_status'][$key]['count']  += 1;
        }

        // Transactions
        $transElements = array('total_commitments' , 'total_disbursements' , 'total_expenditure' , 'total_incoming_funds' , 'total_reimbursements');
        foreach($transElements as $transElement){
            $currency = $out['default_currency_for_amounts'];
            $rate = 1;
            if($out[$transElement] && $currency && ($currency != DEFAULT_CURRENCY)){
                if($out['start_actual_iso_date'] && (strtotime($out['start_actual_iso_date']) <= strtotime(date('Y-m-d'))) ) {
                    $valueDate = date('Y-m-d' , strtotime($out['start_actual_iso_date']));
                    $rate = $exchange->getExchangeRateFor($valueDate , DEFAULT_CURRENCY , $currency);
                } else {
                    $valueDate = date('Y-m-d');
                    $rate = $exchange->getExchangeRateFor($valueDate , DEFAULT_CURRENCY , $currency);
                }
            }

            $transValue = $out[$transElement] * $rate;
            $outputData[DEFAULT_ORG]['transaction'][$transElement]['value']  += $transValue;
            $outputData[$reportingOrgName]['transaction'][$transElement]['value']  += $transValue;
        }

        // Sectors
        $sectorData = $out['sector_codes'];
        if($sectorData){
            $sectors = explode(';' , $sectorData);
            foreach($sectors as $sectorCode){
                $sectorCode = trim($sectorCode);
                $sector = $aidConnector->getNames('sector' , $sectorCode);
                if(!$sector) continue;
                
                $outputData[DEFAULT_ORG]['sectors'][$sector]['count']  += 1;
                $outputData[$reportingOrgName]['sectors'][$sector]['count']  += 1;
            }
        }

        // Recipient Region
        $recipientRegionCodes = $out['recipient_region_codes'];
        if($recipientRegionCodes){
            $regions = explode(';' , $recipientRegionCodes);
            foreach($regions as $regionCode){
                $regionCode = trim($regionCode);
                if(!$regionCode) continue;
                $region = $regionCode;
                if($regionCode){
                    $regionName = $aidConnector->getNames('recipient_region' , $regionCode);
                }

                $outputData[DEFAULT_ORG]['recipient']['region'][$region]['count']  += 1;
                $outputData[DEFAULT_ORG]['recipient']['region'][$region]['name'] = $regionName;
                $outputData[$reportingOrgName]['recipient']['region'][$region]['count']  += 1;
                $outputData[$reportingOrgName]['recipient']['region'][$region]['name'] = $regionName;
            }
        }

        // Recipient Country
        $recipientCountryCodes = $out['recipient_country_codes'];
        if($recipientCountryCodes){
            $countrys = explode(';' , $recipientCountryCodes);
            foreach($countrys as $countryCode){
                $countryCode = trim($countryCode);
                if(!$countryCode) continue;
                $country = $countryCode;
                if($countryCode){
                    $countryName = $aidConnector->getNames('recipient_country' , $countryCode);
                }
                
                $outputData[DEFAULT_ORG]['recipient']['country'][$country]['count']  += 1;
                $outputData[DEFAULT_ORG]['recipient']['country'][$country]['name']  = utf8_encode($countryName);
                $outputData[$reportingOrgName]['recipient']['country'][$country]['count']  += 1;
                $outputData[$reportingOrgName]['recipient']['country'][$country]['name']  = utf8_encode($countryName);
            }
        }

        // Activities
        $outputData[$reportingOrgName]['activities'][$out['iati_identifier']] = $out['aid_project_title'];
    }

    $reportingOrgs = array_keys($outputData);
    if(($key = array_search('All' , $reportingOrgs)) !== false) {
        unset($reportingOrgs[$key]);
    }
    asort($reportingOrgs);
    array_unshift($reportingOrgs , 'All');
    foreach($outputData as $reportingOrgName => $reportingOrgData){
        $reportingOrgData['all_reporting_orgs'] = $reportingOrgs;
        $outData = (object) $reportingOrgData;
        $reportingOrgName = preg_replace("/-| /", '_', strtolower($reportingOrgName));
        $fp = fopen($outputDir."/$reportingOrgName.json" , 'w');
        fwrite($fp, json_encode($outData));
        fclose($fp);
    }
    exec('chmod -R 777 '. INPUT_JSON_DIR . '*.json');
?>
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
        $reportingOrgName = $out['reporting_organisation'];
        /*
        $elements = array('activity_status');
        $activityDatae =
        foreach($elements as $element){
            $outputData['combined'][$element][$out[$element]]['count']  += 1;
            $outputData['combined'][$element][$out[$element]]['activities'][] = $out['full_details'];
            $outputData[$reportingOrgName][$element][$out[$element]]['count']  += 1;
            $outputData[$reportingOrgName][$element][$out[$element]]['activities'][] = $out['full_details'];
        }
        */

        $activityStatus = array('Completion' , 'Post-completion' , 'Implementation' , 'Pipeline/identification' , 'Cancelled');
        foreach($activityStatus as $element){
            $statusCode = $out['activity_status_code'];
            $status = $aidConnector->getNames('activity_status' , $statusCode);
            if($status != $element) continue;
            $key = preg_replace('/(\-|\/)/' , '_' , strtolower($element));
            $outputData[DEFAULT_ORG]['activity_status'][$key]['count']  += 1;
            $outputData[DEFAULT_ORG]['activity_status'][$key]['activities'][] = $out['full_details'];
            $outputData[$reportingOrgName]['activity_status'][$key]['count']  += 1;
            $outputData[$reportingOrgName]['activity_status'][$key]['activities'][] = $out['full_details'];
        }

        //activity dates
        $dateElements = array('start_planned' , 'start_actual' , 'end_planned' , 'end_actual');
        foreach($dateElements as $dateElement){
            $ele = $dateElement."_iso_date";
            if($out[$ele] == '') continue;
            $date = strtotime($out[$ele]);
            if($date < strtotime('1999')) continue;
            $year = date('Y' , $date);
            $outputData[DEFAULT_ORG]['activity_dates'][$year][$dateElement]['count']  += 1;
            $outputData[DEFAULT_ORG]['activity_dates'][$year][$dateElement]['activities'][]  = $out['full_details'];
            $outputData[$reportingOrgName]['activity_dates'][$year][$dateElement]['count']  += 1;
            $outputData[$reportingOrgName]['activity_dates'][$year][$dateElement]['activities'][]  = $out['full_details'];
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
            //$outputData[DEFAULT_ORG]['transaction'][$transElement]['count']  += ($transValue)?0:1;
            $outputData[$reportingOrgName]['transaction'][$transElement]['value']  += $transValue;
            //$outputData[$reportingOrgName]['transaction'][$transElement]['count']  += ($transValue)?0:1;
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
                $outputData[DEFAULT_ORG]['sectors'][$sector]['activities'][]  = $out['full_details'];
                $outputData[$reportingOrgName]['sectors'][$sector]['count']  += 1;
                $outputData[$reportingOrgName]['sectors'][$sector]['activities'][]  = $out['full_details'];
            }
        }

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
                $outputData[DEFAULT_ORG]['recipient']['region'][$region]['activities'][]  = $out['full_details'];
                $outputData[$reportingOrgName]['recipient']['region'][$region]['count']  += 1;
                $outputData[$reportingOrgName]['recipient']['region'][$region]['name'] = $regionName;
                $outputData[$reportingOrgName]['recipient']['region'][$region]['activities'][]  = $out['full_details'];
            }
        }

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
                $outputData[DEFAULT_ORG]['recipient']['country'][$country]['name']  = $countryName;
                $outputData[DEFAULT_ORG]['recipient']['country'][$country]['activities'][]  = $out['full_details'];

                $outputData[$reportingOrgName]['recipient']['country'][$country]['count']  += 1;
                $outputData[$reportingOrgName]['recipient']['country'][$country]['name']  = $countryName;
                $outputData[$reportingOrgName]['recipient']['country'][$country]['activities'][]  = $out['full_details'];
            }
        }

        //$outputData[$reportingOrgName]['activities'][$out['iati_identifier']] = $out['full_details'];
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
        $reportingOrgName = preg_replace("/-| /" , '_' , strtolower($reportingOrgName));
        $fp = fopen($outputDir."/$reportingOrgName.json" , 'w');
        fwrite( $fp , json_encode($outData));
        fclose($fp);
    }
    exec('chmod -R 777 '. INPUT_JSON_DIR);
?>
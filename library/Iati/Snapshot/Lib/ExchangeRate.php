<?php
require_once(__dir__."/../Config.php");

/**
 * Class to handle api request to openexchangerate.org and get the rates.
 */
class ExchangeRate{
    
    protected $fileLocation = CURRENCIES_DIR;
    protected $appId = '9fe5f68bc41a4b7eaf297c38f106954b';
    protected $apiUrl = "http://openexchangerates.org/api/historical/";
    
    public function getExchangeRateFor($date ,$to , $from = 'USD')
    {
        $filename = $this->fileLocation.$date.".json";
        if(file_exists($filename)){
            $fp = fopen($filename , 'r');
            $this->rateListJson = fread($fp , filesize($filename));
            fclose($fp);
        } else {
            $this->getRateListFromApi($date);
        }
        return $this->getRate($to, $from);
    }
    
    public function getRate($to , $from = 'USD')
    {
        if(!$this->rateListJson) return 1;
        
        $rateList = json_decode($this->rateListJson);
        $rates = $rateList->rates;
        if($from == 'USD'){
            return $rates->$to;
        } else {
            if($to == 'USD'){
                $usdToFromCurrency = $rates->$from;
                $rate = 1/$usdToFromCurrency;
                return $rate;
            } else {
                $usdToToCurrency = $rates->$to;
                $usdToFromCurrency = $rates->$from;
                $rate = $usdToToCurrency/$usdToFromCurrency;
                return $rate;
            }
        }
        
    }
    
    public function getRateListFromApi($date)
    {
        $curlUrl = $this->getApiUrl($date);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$curlUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec ($ch);
        if(!curl_errno($ch)){
            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200){
                $data = json_decode($response);
                if(isset($data->error)) { print ("Error fetching data from api"); exit; }
                $this->rateListJson = $response;
                // Save response to file for future use.
                $filename = $this->fileLocation.$date.".json";
                $fp = fopen($filename , 'w');
                fwrite($fp , $response);
                fclose($fp);
                return;
            } else {
                print ("Error fetching data from api");
                print_r($response);
                return;
            }
        } else {
            print ("Error fetching data from api");
            print_r($response);
            return ;
        }
    }
    
    public function getApiUrl($date)
    {
        $historicalDate = date('Y-m-d',strtotime($date));
        if(!$historicalDate) print("Please provide a valid date");
        
        $url = $this->apiUrl.$historicalDate.".json?app_id=".$this->appId;
        return $url;
    }
}
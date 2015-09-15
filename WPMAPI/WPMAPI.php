<?php

namespace WPMAPI;

class Exception extends \Exception{
}

class WPMAPI {

    private $base_url = '';
    private $apikey = '';
    private $secret = '';
    private $ver = '1.0';

    public function __construct($base_url, $apikey, $secret){

        if($base_url)
            $this->base_url = $base_url;

        if($apikey)
            $this->apikey = $apikey;

        if($secret)
            $this->secret = $secret;
    }

    /**
     * @brief   Processes api calls.
     *
     * @param   $service        String      Service name.
     * @param   $method         String      Method name.
     * @param   $params         Array       Parameters
     *
     * @retval  object  JSON request.
     */

    public function request($service, $method, $params){

        $ch = curl_init();
        $timestamp = gmdate('U');
        $sig = md5($this->apikey . $this->secret . $timestamp);

        curl_setopt($ch, CURLOPT_URL, $this->base_url . "/" . $service . "/" . $this->ver . "/" . $method. '?' . $params . 'apikey=' . $this->apikey . '&sig='.$sig);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $headers = curl_getinfo($ch);

        // close curl
        curl_close($ch);

        // return XML data
        if ($headers['http_code'] != '200') {
        //   echo "An error has occurred. HTTP Code: " . $headers['http_code'];
           return false;
        } else {
           return($data);
        }

    }

    /**
     * @brief   Returns a list of all monitoring locations available.
     *
     * @retval  string  JSON request.
     */

    public function monitorGetMonitoringLocations(){

        $service = 'monitor';
        $method = 'locations';
        return $this->request($service, $method, $params=NULL);

    }

    /**
     * @brief   Returns all of the data that is found when looking at your list of monitors in the web portal.
     *
     * @param   $monitorID          String
     *
     * @retval  string  JSON request.
     */

    public function monitorGetSummary($monitorID){

        $service = 'monitor';
        $method = $monitorID . '/summary';
        return $this->request($service, $method, $params = NULL);

    }

    /**
     * @brief   Returns all samples associated to this monitor for a given time period.
     *
     * @param   $monitorID          String
     * @param   $params             Array of parameters:
     *      @param   startDate          String ISO 8601 formatted. Ex. 2012-03-02
     *      @param   endDate            String ISO 8601 formatted. Ex. 2012-03-02
     *      @param   offset             Int    From which position in the return list you wish to start. At most, 2000 records will be returned.
     *
     * @retval  string  JSON request.
     */

    public function monitorGetSamples($monitorID, $params){

        if(!isset($params['startDate']))
            return "Start date missing in method call.\n";                        #todo: throw exception?  return string?

        if(!isset($params['endDate']))
            return "End date missing in method call.\n";                          #todo: throw exception? return string?

        if(!isset($params['offset']))
            $params['offset'] = NULL;

        $service = 'monitor';
        $method = $monitorID . '/sample';
        $params = $this->buildQueryString($params);

        return $this->request($service, $method, $params);

    }

    /**
     * @brief   Returns the raw, HTTP Archive (HAR) data for a particular sample.
     *
     * @param   $monitorID          String
     * @param   $sampleID           String
     *
     * @retval  string  JSON request.
     */

    public function monitorGetRawSampleData($monitorID, $sampleID){

        if(!isset($monitorID) || !isset($sampleID))
            return "Missing parameters in method call.";

        $service = 'monitor';
        $method = $monitorID . '/sample/' . $sampleID;

        return $this->request($service, $method, $params=NULL);

    }

    /**
     * @brief   Returns the aggregated sample information for a given period of time.
     *
     * @param   $monitorID          String
     * @param   $params             Array of parameters:
     *      @param   startDate          String ISO 8601 formatted. Ex. 2012-03-02
     *      @param   endDate            String ISO 8601 formatted. Ex. 2012-03-02
     *      @param   frequency          Enumerated     Aggregation period. ('day' or 'hour').
     *      @param   offset             Int    From which position in the return list you wish to start. At most, 2000 records will be returned.
     *      @param   groupBy            Enumerated    When selected, the data will be aggregated by the selected 'groupBy'. ('location' or 'step').
     *
     * @retval  string  JSON request.
     */

    public function monitorGetAggregateSampleData($monitorID, $params){

        if(!isset($params['startDate']))
            return "Start date missing in method call.\n";                        #todo: throw exception?  return string?

        if(!isset($params['endDate']))
            return "End date missing in method call.\n";                          #todo: throw exception? return string?

        if(!isset($params['frequency']))
            return "Frequency missing in method call.\n";                         #todo: throw exception? return string?

        if(!isset($params['offset']))
            $params['offset'] = NULL;

        if(!isset($params['groupBy']))
            $params['offset'] = NULL;

        $service = 'monitor';
        $method = $monitorID . '/aggregate';
        $params = $this->buildQueryString($params);

        return $this->request($service, $method, $params);

    }

    /**
     * @brief   Returns information for a specific monitor associated with your account.
     *
     * @param   $monitorID          String
     *
     * @retval  string  JSON request.
     */

    public function monitorGetAMonitor($monitorID) {

        $service = 'monitor';
        $method = $monitorID;

        return $this->request($service, $method, $params=NULL);

    }

    /**
     * @brief   Returns a list of all monitors associated with your account, along with information about each.
     *
     * @retval  string  JSON request.
     */

    public function monitorListMonitors() {

        $service = 'monitor';
        $method = '';

        return $this->request($service, $method, $params=NULL);

    }


    /**
     * @brief   Converts params in associative array into url-encoded query string.
     *
     * @param   $params          Associative array.
     *
     * @retval  string  url-encoded query string.
     */

    private function buildQueryString($params){

        return http_build_query($params, '', '&') . '&';

    }

}

?>

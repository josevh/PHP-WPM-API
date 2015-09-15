<?php
namespace WPMAPI;

require 'WPMAPI.php';

/**
 *
 */
class Monitoring extends WPMAPI
{

    // function __construct(argument)
    // {
    //     # code...
    // }

    /**
     * @brief   Returns a list of all monitoring locations available.
     *
     * @retval  string  JSON request.
     */
    public function getMonitoringLocations(){
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
    public function getSummary($monitorID){
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
    public function getSamples($monitorID, $params){
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
    public function getRawSampleData($monitorID, $sampleID){
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
    public function getAggregateSampleData($monitorID, $params){
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
    public function getAMonitor($monitorID) {
        $service = 'monitor';
        $method = $monitorID;
        return $this->request($service, $method, $params=NULL);
    }
    /**
     * @brief   Returns a list of all monitors associated with your account, along with information about each.
     *
     * @retval  string  JSON request.
     */
    public function listMonitors() {
        $service = 'monitor';
        $method = '';
        return $this->request($service, $method, $params=NULL);
    }
}

?>
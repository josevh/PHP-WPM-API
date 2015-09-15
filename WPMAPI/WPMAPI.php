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
        //    echo "An error has occurred. HTTP Code: " . $headers['http_code'] . "<br>";
           return false;
        } else {
           return($data);
        }
    }

    /**
     * @brief   Converts params in associative array into url-encoded query string.
     *
     * @param   $params          Associative array.
     *
     * @retval  string  url-encoded query string.
     */
    public function buildQueryString($params){
        return http_build_query($params, '', '&') . '&';
    }
}

?>

<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class InternetBs_Api 
{
    private $host = '';
    private $apiKey = '';
    private $password = '';

    public function __construct()
    {
    	
	$ci =& get_instance();
	#guzzle library add to use guzzle
	$ci->load->config('internetbs_api', TRUE);
	$this->host = $ci->config->item('host', 'internetbs_api');;
     	$this->apiKey = $ci->config->item('api_key', 'internetbs_api');;
     	$this->password = $ci->config->item('password', 'internetbs_api');;

    }

    public function execute($commandName, $params = null)
    {
        $params['apikey'] = $this->apiKey;
        $params['password'] = $this->password;
        $params['responseformat'] = 'JSON';

        $apiRequestUrl = 'https://' . $this->host . '/' . $commandName;

        # guzzle client define
  	$client     = new GuzzleHttp\Client();
        $res = $client->request('POST', $apiRequestUrl, array(
            'query' => $params
        ));

        return json_decode($res->getBody());
    }
}
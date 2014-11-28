<?php

namespace Jacobemerick\BigstockAPI;

use Presto\Presto;
use Presto\Response;

class Client
{

    // main endpoint for API
    protected $api_endpoint = 'api.bigstockphoto.com/2/';

    // holders for authentication params
    protected $api_id;
    protected $secret_key;

    // option to make all requests as SSL
    protected $use_ssl;

    // holder for optional CURL options
    protected $options;

    /**
     * basic construct
     *
     * @param  string  $api_id   identifying partner key from bigstock
     * @param  array   $options  list of optional CURL settings for all requests
     */
    public function __construct($api_id, array $options = array())
    {
        $this->api_id = $api_id;
        $this->options = $options;
    }

    /**
     * fetch categories from api
     * on error an exception will be thrown
     *
     * @param   string  $language  optional language to fetch categories in
     * @param   array   $options   list of optional CURL settings for all requests
     * @return  array              decoded response from API
     */
    public function fetchCategories($language = '', array $options = array())
    {
        $options = $this->options + $options;
        $request = new Presto($options);

        $endpoint = "http://{$this->api_endpoint}{$this->api_id}/categories";
        if (!empty($language)) {
            $response = $request->get($endpoint, ['language' => $language]);
        } else {
            $response = $request->get($endpoint);
        }

        return $this->processResponse($response);
    }

    protected function processResponse(Response $response)
    {
        if ($response->is_success != true) {
            throw new Exception\NetworkException($response);
        }
        if ($response->http_code != 200) {
            throw new Exception\APIException($response);
        }

        // todo - catch non json responses
        $data = json_decode($response->data, true);
        return $data['data'];
    }

}


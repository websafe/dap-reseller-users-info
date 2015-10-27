<?php

namespace Websafe\DirectAdmin\Plugins\ResellerUsersInfo;

use Zend\Config\Config,
    Zend\Uri\Http as Uri,
    Zend\Http\Client,
    Zend\Http\Request;

/**
 * 
 */
class Plugin
{
    /**
     * Configuration
     * 
     * @var Zend\Config\Config
     */
    protected $config;
    /**
     * HTTP Uri
     * 
     * @var Zend\Uri\Http
     */
    protected $uri;
    /**
     * HTTP Client
     * 
     * @var Zend\Http\Client 
     */
    protected $client;
    /**
     * HTTP Response
     * 
     * @var Zend\Http\Response
     */
    protected $response;
    /**
     * Initialize configuration
     * 
     * @return \Websafe\DirectAdmin\Plugins\ResellerUsersInfo\Plugin
     */
    protected function initConfig()
    {
        /* */
        $configArray  = array(
            'scheme'  => getenv('SSL') == 1 ? 'https' : 'http',
            'host'    => '127.0.0.1',
            'port'    => getenv('SERVER_PORT'),
            'session' => getenv('SESSION_ID'),
            'key'     => getenv('SESSION_KEY'),
        );
        /* */
        $this->config = new Config($configArray);
        return $this;
    }
    /**
     * Initialize uri
     * 
     * @return \Websafe\DirectAdmin\Plugins\ResellerUsersInfo\Plugin
     */
    protected function initUri()
    {
        /* */
        $this->uri = new Uri();
        $this->uri->setScheme($this->config->scheme);
        $this->uri->setHost($this->config->host);
        $this->uri->setPort($this->config->port);
        return $this;
    }
    /**
     * Initialize HTTP client
     * 
     * @return \Websafe\DirectAdmin\Plugins\ResellerUsersInfo\Plugin
     */
    protected function initClient()
    {
        /* */
        $this->client = new Client();
        $this->client->setCookies(
            array(
                'session' => $this->config->session,
                'key'     => $this->config->key
            )
        );
        $this->client->setMethod(Request::METHOD_GET);
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initConfig();
        $this->initUri();
        $this->initClient();
    }
    /**
     * Retrieve array of users created by the current reseller.
     * 
     * @return array
     */
    public function getUsers()
    {
        $this->uri->setPath('/CMD_API_SHOW_USERS');
        $this->uri->setQuery(null);
        $this->client->setUri($this->uri);
        $this->client->setMethod(Request::METHOD_GET);
        /* */
        try {
            /* */
            $this->response = $this->client->send();
            /* */
            if ($this->response->isSuccess()) {
                // not having X-directadmin means we're authenticated
                if (!$this->response->getHeaders()->has('X-directadmin')) {
                    \parse_str($this->response->getBody(), $result);
                    return $result['list'];
                }
                else {
                    // we're not authenticated
                    echo "Not authenticated";
                    return array();
                }
            }
            else {
                // HTTP response other than 200, some error
                // probably not found, wrong API command etc.
                return array();
            }
        }
        catch (Exception $e) {
            /* */
            echo 'Problem: ' . $e->getMessage();
            return array();
        }
    }
    /**
     * Retrive user's configuration
     * 
     * @param string $user
     * @return array
     */
    public function getUserConfig($user)
    {
        $this->uri->setPath('/CMD_API_SHOW_USER_CONFIG');
        $this->uri->setQuery(null);
        $this->client->setUri($this->uri);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet(array('user' => $user));
        /* */
        try {
            /* */
            $this->response = $this->client->send();
            /* */
            if ($this->response->isSuccess()) {
                // not having X-directadmin means we're authenticated
                if (!$this->response->getHeaders()->has('X-directadmin')) {
                    \parse_str($this->response->getBody(), $result);
                    return $result;
                }
                else {
                    // we're not authenticated
                    echo "Not authenticated";
                    return array();
                }
            }
            else {
                // HTTP response other than 200, some error
                // probably not found, wrong API command etc.
                return array();
            }
        }
        catch (Exception $e) {
            /* */
            echo 'Problem: ' . $e->getMessage();
            return array();
        }
    }
}

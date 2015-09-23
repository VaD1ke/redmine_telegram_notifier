<?php
namespace App\Http;

use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Http\Response;

/**
 * Http provider
 *
 * @category   App
 * @package    App
 * @subpackage Http
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
abstract class Provider
{
    /**
     * Connection adapter
     */
    const CONNECTION_CURL_ADAPTER = 'Zend\Http\Client\Adapter\Curl';

    /**
     * Http client
     *
     * @var \Zend\Http\Client
     */
    protected $_httpClient;

    /**
     * Get Http client
     *
     * @param string $url         URL
     * @param bool   $resetParams Reset params
     * @param string $method      Method
     * @param string $adapterName Adapter name
     *
     * @return Client
     */
    protected function _getHttpClient(
        $url, $resetParams = false, $method = Request::METHOD_GET, $adapterName = self::CONNECTION_CURL_ADAPTER
    ) {
        $this->_httpClient = new Client($url);

        $this->_httpClient->resetParameters($resetParams);

        $this->_httpClient->setMethod($method)->setAdapter($adapterName);

        return $this->_httpClient;
    }
}

<?php
namespace App\Bot;

use \Zend\Http\Client;
use \Zend\Http\Request;
use Zend\Http\Response;

/**
 * Bot API
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Api
{
    /**
     * Get bot info method
     */
    const GET_BOT_INFO_METHOD = 'getMe';
    /**
     * Get bot updates method
     */
    const GET_BOT_UPDATES_METHOD = 'getUpdates';
    /**
     * Send message method
     */
    const SEND_MESSAGE_METHOD = 'sendMessage';
    /**
     * Connection adapter
     */
    const CONNECTION_ADAPTER = 'Zend\Http\Client\Adapter\Curl';

    /**
     * Bot API URL
     *
     * @var string
     */
    protected $_url;
    /**
     * Http client
     *
     * @var \Zend\Http\Client
     */
    protected $_httpClient;

    /**
     * Object initialization
     *
     * @param mixed $config Config parser
     */
    public function __construct($config)
    {
        $this->_url = 'https://api.telegram.org/bot' . $config->getBotToken() . '/';
    }

    /**
     * Get updates
     *
     * @param string|null $offset Offset
     *
     * @return null|string
     *
     * @throws \Zend\Http\Exception\ExceptionInterface
     */
    public function getUpdates($offset = null)
    {
        $client = $this->_getHttpClient(self::GET_BOT_UPDATES_METHOD, true);

        if ($offset) {
            $client->setParameterGet([ 'offset' => $offset ]);
        }

        $response = $client->send();
        if ($response->getStatusCode() == Response::STATUS_CODE_200) {
            return $response->getBody();
        }

        return null;
    }

    /**
     * Send message
     *
     * @param integer $chatId  Chat ID
     * @param string  $message Message
     *
     * @return null|string
     *
     * @throws \Zend\Http\Exception\ExceptionInterface
     */
    public function sendMessage($chatId, $message)
    {
        $data = [
            'chat_id'                  => $chatId,
            'text'                     => $message,
            'disable_web_page_preview' => 'true',
        ];

        $client = $this->_getHttpClient(self::SEND_MESSAGE_METHOD, true, Request::METHOD_POST);

        $client->setParameterPost($data);

        $response = $client->send();
        if ($response->getStatusCode() == Response::STATUS_CODE_200) {
            return $response->getBody();
        }

        return null;
    }


    /**
     * Get Http client
     *
     * @param string $apiMethod   API method
     * @param bool   $resetParams Reset params
     * @param string $method      Method
     *
     * @return Client
     */
    protected function _getHttpClient(
        $apiMethod = self::GET_BOT_INFO_METHOD, $resetParams = false, $method = Request::METHOD_GET
    ) {
        $this->_httpClient = new Client($this->_url . $apiMethod);

        if ($resetParams) {
            $this->_httpClient->resetParameters(true);
        }
        $this->_httpClient->setMethod($method)->setAdapter(self::CONNECTION_ADAPTER);

        return $this->_httpClient;
    }
}

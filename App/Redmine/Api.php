<?php
namespace App\Redmine;

use App\Http\Provider as HttpProvider;
use Zend\Http\Response;

/**
 * Bot API
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Api extends HttpProvider
{
    /**
     * Get issues method
     */
    const GET_ISSUES_METHOD = 'issues.json';

    /**
     * Param  current user ID
     */
    const PARAM_CURRENT_USER_ID = 'me';
    /**
     * Param issues limit
     */
    const PARAM_ISSUES_LIMIT = 100;

    /**
     * Redmine API URL
     *
     * @var string
     */
    protected $_url = 'http://redmine.oggettoweb.com/';
    /**
     * Redmine API key
     *
     * @var string
     */
    protected $_apiKey;
    /**
     * Redmine user ID
     *
     * @var string
     */
    protected $_userId = self::PARAM_CURRENT_USER_ID;
    /**
     * Redmine issues limit
     *
     * @var string
     */
    protected $_limit = self::PARAM_ISSUES_LIMIT;

    /**
     * Get issues
     *
     * @return null|string
     *
     * @throws \Zend\Http\Exception\ExceptionInterface
     */
    public function getIssues()
    {
        $client = $this->_getHttpClient($this->_url . self::GET_ISSUES_METHOD, true);

        $client->setParameterGet([
            'assigned_to_id' => $this->getUserId(),
            'key'            => $this->getApiKey(),
            'limit'          => $this->getIssueLimit(),
        ]);

        $response = $client->send();
        if ($response->getStatusCode() == Response::STATUS_CODE_200) {
            return $response->getBody();
        }

        return null;
    }


    /**
     * Set api key
     *
     * @param string $apiKey API key
     *
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
        return $this;
    }
    /**
     * Get Api key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->_apiKey;
    }

    /**
     * Set user ID
     *
     * @param string $userId User ID
     *
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->_userId = $userId;
        return $this;
    }
    /**
     * Get user ID
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->_userId;
    }

    /**
     * Set issue limit
     *
     * @param string $limit Issues limit
     *
     * @return $this
     */
    public function setIssueLimit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }
    /**
     * Get issues limit
     *
     * @return string
     */
    public function getIssueLimit()
    {
        return $this->_limit;
    }

    /**
     * Get URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }
}

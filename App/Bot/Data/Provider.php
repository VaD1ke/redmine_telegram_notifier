<?php
namespace App\Bot\Data;

/**
 * Bot data provider
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Provider
{
    /**
     * Api key
     *
     * @var string
     */
    const API_KEY = 'key';

    /**
     * Provider
     *
     * @var \App\DB\Provider
     */
    protected $_provider;


    /**
     * Get update id
     *
     * @return mixed
     */
    public function getBotUpdateId()
    {
        $this->_getProvider();
        return $this->_provider->loadLastUpdate();
    }

    /**
     * Set update id
     *
     * @param integer $updateId Update ID
     *
     * @return void
     */
    public function setBotUpdateId($updateId)
    {
        $this->_getProvider();
        $this->_provider->updateLastUpdate($updateId);
    }


    /**
     * Get provider
     *
     * @return \App\DB\Provider
     */
    protected function _getProvider()
    {
        if (!$this->_provider) {
            $this->_provider = new \App\DB\Provider();
        }
        return $this->_provider;
    }
}

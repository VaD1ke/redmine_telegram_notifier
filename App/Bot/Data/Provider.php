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
    const API_KEY = '126198396:AAHzDX_utzwNxs-j1cx4IQK5ivpCek7zl2Y';

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
        $lastUpdate = $this->_provider->loadLastUpdate();

        if (!$lastUpdate) {
            return null;
        }

        return current(current($lastUpdate));
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
            $this->_provider = new \App\DB\Adapter\Provider();
        }
        return $this->_provider;
    }
}

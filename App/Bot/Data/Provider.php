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
     * Provider
     *
     * @var \App\DB\Provider
     */
    protected $_provider;

    /**
     * Object initialization
     *
     * @param \App\DB\Adapter\Provider $provider Provider
     */
    public function __construct(\App\DB\Adapter\Provider $provider)
    {
        $this->_provider = $provider;
    }

    /**
     * Get update id
     *
     * @return mixed
     */
    public function getBotUpdateId()
    {
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
        $this->_provider->updateLastUpdate($updateId);
    }

    /**
     * Get provider
     *
     * @return \App\DB\Adapter\Provider|\App\DB\Provider
     */
    public function getProvider()
    {
        return $this->_provider;
    }
}

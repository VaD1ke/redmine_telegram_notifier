<?php
namespace App\Bot;

/**
 * Handles updates from API
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Handler
{
    /**
     * Command models alias
     */
    const COMMAND_MODELS_ALIAS = 'App\\Bot\\Command\\';

    /**
     * Dependency injection
     *
     * @var \Zend\Di\Di
     */
    private $_di;

    /**
     * Data provider
     *
     * @var Data\Provider
     */
    protected $_dataProvider;
    /**
     * Update helper
     *
     * @var Helper\Update
     */
    protected $_updateHelper;

    /**
     * Object initialization
     *
     * @param \Zend\Di\Di $di
     */
    public function __construct(\Zend\Di\Di $di, Data\Provider $dataProvider, Helper\Update $updateHelper)
    {
        $this->_di = $di;
        $this->_dataProvider = $dataProvider;
        $this->_updateHelper = $updateHelper;
    }

    /**
     * Handle Bot API updates
     *
     * @param array $updates Updates from Bot API
     *
     * @return void
     */
    public function handleBotApiUpdates(array $updates)
    {
        $updateId = $this->_dataProvider->getBotUpdateId();

        foreach ($updates['result'] as $update) {
            $message  = trim($this->_updateHelper->getMessageText($update));
            $updateId = $this->_updateHelper->getIncrementedUpdateId($update);

            if (!$this->_isCommandExist($this->_getCommandClassName($message))) {
                continue;
            }

            /** @var ICommand $commandModel */
            $commandModel = $this->_di->get($this->_getCommandClassName($message));
            $commandModel->execute($update);
        }

        $this->_dataProvider->setBotUpdateId($updateId);
    }

    /**
     * Is command exist
     *
     * @param $commandClass
     *
     * @return bool
     */
    protected function _isCommandExist($commandClass)
    {
        return in_array($commandClass, $this->_di->instanceManager()->getClasses());
    }

    /**
     * Get command class name
     *
     * @param string $message Message
     *
     * @return string
     */
    protected function _getCommandClassName($message)
    {
        $command = stristr($message, ' ', true);

        if ($command === false) {
            $command = $message;
        }

        if (strpos($command, '/') !== 0) {
            return '';
        }

        return self::COMMAND_MODELS_ALIAS . ucfirst(substr($command, 1));
    }
}

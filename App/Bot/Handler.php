<?php
namespace App\Bot;

use App\App;
use \App\Bot\Api as BotApi;

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
    const COMMAND_MODELS_ALIAS = '\\App\\Bot\\Command\\';


    /**
     * Handle Bot API updates
     *
     * @param array  $updates Updates from Bot API
     * @param BotApi $botApi  Bot API
     *
     * @return void
     */
    public function handleBotApiUpdates(array $updates, BotApi $botApi)
    {
        $dataProvider = new Data\Provider();

        $updateId = $dataProvider->getBotUpdateId();

        $updateHelper = new Helper\Update();
        foreach ($updates['result'] as $update) {
            $updateId = $updateHelper->getIncrementedUpdateId($update);
            $message  = trim($updateHelper->getMessageText($update));

            /** @var ICommand $commandModel */
            $commandModel = App::getClassInstance($this->_getCommandClassName($message), $botApi);
            if ($commandModel) {
                $commandModel->execute($update);
            }
        }

        $dataProvider->setBotUpdateId($updateId);
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

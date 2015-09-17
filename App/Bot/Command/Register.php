<?php
namespace App\Bot\Command;

use \App\Bot\ICommand;
use \App\Bot\Api as BotApi;
use App\Bot\Data\Provider as DataProvider;
use \App\DB\Provider as DbProvider;
use App\Bot\Helper\Message as HelperMessage;
use \App\Bot\Helper\Update as HelperUpdate;

/**
 * Register command
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Register implements ICommand
{
    /**
     * Execute command
     *
     * @param array $update Update
     *
     * @return void
     */
    public function execute(array $update)
    {
        $subscriber = $this->_addSubscriber($update);

        if (!$subscriber) {
            return;
        }
        $botApi = new BotApi(DataProvider::API_KEY);

        $botApi->sendMessage( $subscriber['chat_id'], $this->_getSubscribeMessage($subscriber['name']) );
    }


    /**
     * Add subscriber
     *
     * @param array $update Update
     *
     * @return array
     */
    protected function _addSubscriber(array $update)
    {
        $updateHelper  = new HelperUpdate();
        $messageHelper = new HelperMessage();

        $chatData   = [];
        $chatId     = $updateHelper->getChatId($update);
        $chatName   = $updateHelper->getChatName($update);
        $redmineKey = $messageHelper->getArgumentFromMessage($updateHelper->getMessageText($update));

        if (!$redmineKey) {
            return $chatData;
        }

        $chatData = [ 'chat_id' => $chatId, 'name' => $chatName ];

        $provider = new DbProvider();
        if ($chat = $provider->loadChat($chatId)) {
            $provider->updateChat($chat['chat_id'], $chatName, $redmineKey);
        } else {
            $provider->addChat($chatId, $chatName, $redmineKey);
        }

        return $chatData;
    }

    /**
     * Get sign up message
     *
     * @param string $chatName Chat name
     *
     * @return string
     */
    protected function _getSubscribeMessage($chatName)
    {
        $message = $chatName . ', ' . 'Вы успешно подписались на уведомления с Redmine!';
        return $message;
    }
}

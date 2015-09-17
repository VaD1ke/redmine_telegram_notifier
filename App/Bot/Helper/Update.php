<?php
namespace App\Bot\Helper;

/**
 * Bot API update helper
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Update
{
    /**
     * Get incremented update ID
     *
     * @param array $update Update
     *
     * @return integer
     */
    public function getIncrementedUpdateId(array $update)
    {
        return ++$update['update_id'];
    }

    /**
     * Get chat id
     *
     * @param array $update Update
     *
     * @return integer
     */
    public function getChatId(array $update)
    {
        return $update['message']['chat']['id'];
    }

    /**
     * Get chat name
     *
     * @param array $update Update
     *
     * @return string
     */
    public function getChatName(array $update)
    {
        if (array_key_exists('first_name', $update['message']['chat'])) {
            $chatName = $update['message']['chat']['first_name'] . ' ' . $update['message']['chat']['last_name'];
        } else {
            $chatName = $update['message']['chat']['title'];
        }
        return $chatName;
    }

    /**
     * Get message text
     *
     * @param array $update Update
     *
     * @return string
     */
    public function getMessageText(array $update)
    {
        return $update['message']['text'];
    }
}

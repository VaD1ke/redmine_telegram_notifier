<?php
namespace App\Bot\Helper;

/**
 * Bot API message helper
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Message
{
    /**
     * Get argument from message
     *
     * @param string $message Message
     *
     * @return bool|string
     */
    public function getArgumentFromMessage($message)
    {
        $argument = stristr(trim($message), ' ');
        if ($argument === false) {
            return false;
        }
        return substr($argument, 1);
    }
}

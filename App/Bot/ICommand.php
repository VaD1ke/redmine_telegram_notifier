<?php
namespace App\Bot;

/**
 * Command interface
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
interface ICommand
{
    /**
     * Execute command
     *
     * @param array  $update Update
     *
     * @return void
     */
    public function execute(array $update);
}

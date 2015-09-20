<?php
namespace App\Config;

/**
 * Config parser
 *
 * @category   App
 * @package    App
 * @subpackage App
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Parser
{
    /**
     * Config path
     *
     * @var string
     */
    protected $_configPath;

    /**
     * Object initialization
     */
    public function __construct()
    {
        $this->_configPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'
                             . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'Config.ini';
    }

    /**
     * Get bot api
     *
     * @return string
     */
    public function getBotToken()
    {
        $config = parse_ini_file($this->_configPath);

        return $config['bot_token'];
    }
}

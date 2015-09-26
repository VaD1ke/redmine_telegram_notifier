<?php
namespace App\Redmine\Issue;

use App\Redmine\Api as RedmineApi;
use App\Redmine\Helper\Issue as IssueHelper;

/**
 * Redmine user issue getter
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Getter
{
    /**
     * Redmine key
     *
     * @var string
     */
    protected $_redmineKey;
    /**
     * Redmine API
     *
     * @var RedmineApi
     */
    protected $_redmineApi;
    /**
     * Issue helper
     *
     * @var IssueHelper
     */
    protected $_issueHelper;

    /**
     * is only numbers
     *
     * @var boolean
     */
    private $_isOnlyNumbers;

    /**
     * Object initialization
     *
     * @param RedmineApi  $redmineApi  Redmine API
     * @param IssueHelper $issueHelper Issue helper
     */
    public function __construct(RedmineApi $redmineApi, IssueHelper $issueHelper)
    {
        $this->_redmineApi  = $redmineApi;
        $this->_issueHelper = $issueHelper;
    }

    /**
     * Get issues
     *
     * @return array
     */
    public function getIssues()
    {
        $issues = json_decode(
            $this->_redmineApi->setApiKey($this->getRedmineKey())->getIssues(), $assoc = true
        );

        if (array_key_exists('issues', $issues) && $issues['issues']) {
            if ($this->_isOnlyNumbers) {
                return $this->_getIssueNumbers($issues['issues']);
            }
            return $issues['issues'];
        }

        return [];
    }



    /**
     * Set redmine key
     *
     * @param string $redmineKey Redmine key
     *
     * @return $this
     */
    public function setRedmineKey($redmineKey)
    {
        $this->_redmineKey = $redmineKey;
        return $this;
    }
    /**
     * Get redmine key
     *
     * @return string|null
     */
    public function getRedmineKey()
    {
        return $this->_redmineKey;
    }

    /**
     * Set is only numbers
     *
     * @param $value
     *
     * @return $this
     */
    public function setIsOnlyNumbers($value)
    {
        $this->_isOnlyNumbers = $value;
        return $this;
    }


    /**
     * Get issues numbers
     *
     * @param array $issues
     *
     * @return array
     */
    protected function _getIssueNumbers(array $issues)
    {
        $issueNumbers = [];

        foreach($issues as $issue) {
            $issueNumbers[] = $this->_issueHelper->getIssueId($issue);
        }

        return $issueNumbers;
    }
}

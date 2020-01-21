<?php

namespace srag\Plugins\SrProjectHelper\Job;

use ilCronJob;
use ilSrProjectHelperPlugin;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrProjectHelper\Job
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory
{

    use DICTrait;
    use SrProjectHelperTrait;
    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    /**
     * @var self
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @return ilCronJob[]
     */
    public function newInstances() : array
    {
        return [
            $this->newFetchGitlabInfosJobInstance()
        ];
    }


    /**
     * @param string $job_id
     *
     * @return ilCronJob|null
     */
    public function newInstanceById(string $job_id)/*: ?ilCronJob*/
    {
        switch ($job_id) {
            case FetchGitlabInfosJob::CRON_JOB_ID:
                return $this->newFetchGitlabInfosJobInstance();

            default:
                return null;
        }
    }


    /**
     * @return FetchGitlabInfosJob
     */
    public function newFetchGitlabInfosJobInstance() : FetchGitlabInfosJob
    {
        $job = new FetchGitlabInfosJob();

        return $job;
    }
}

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
 */
final class Factory
{

    use DICTrait;
    use SrProjectHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


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
     * @return FetchGitlabInfosJob
     */
    public function newFetchGitlabInfosJobInstance() : FetchGitlabInfosJob
    {
        $job = new FetchGitlabInfosJob();

        return $job;
    }


    /**
     * @param string $job_id
     *
     * @return ilCronJob|null
     */
    public function newInstanceById(string $job_id) : ?ilCronJob
    {
        switch ($job_id) {
            case FetchGitlabInfosJob::CRON_JOB_ID:
                return $this->newFetchGitlabInfosJobInstance();

            default:
                return null;
        }
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
}

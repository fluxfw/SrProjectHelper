<?php

namespace srag\Plugins\SrGitlabHelper\Job;

use ilCronJob;
use ilCronJobResult;
use ilSrGitlabHelperPlugin;
use srag\DIC\SrGitlabHelper\DICTrait;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;

/**
 * Class FetchGitlabInfosJob
 *
 * @package srag\Plugins\SrGitlabHelper\Job
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FetchGitlabInfosJob extends ilCronJob {

	use DICTrait;
	use SrGitlabHelperTrait;
	const CRON_JOB_ID = ilSrGitlabHelperPlugin::PLUGIN_ID . "_fetch_gitlab_infos";
	const PLUGIN_CLASS_NAME = ilSrGitlabHelperPlugin::class;
	const LANG_MODULE_CRON = "cron";


	/**
	 * FetchGitlabInfosJob constructor
	 */
	public function __construct() {

	}


	/**
	 * Get id
	 *
	 * @return string
	 */
	public function getId(): string {
		return self::CRON_JOB_ID;
	}


	/**
	 * @return string
	 */
	public function getTitle(): string {
		return ilSrGitlabHelperPlugin::PLUGIN_NAME . ": " . self::plugin()->translate(self::CRON_JOB_ID, self::LANG_MODULE_CRON);
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return self::plugin()->translate(self::CRON_JOB_ID . "_description", self::LANG_MODULE_CRON);
	}


	/**
	 * Is to be activated on "installation"
	 *
	 * @return boolean
	 */
	public function hasAutoActivation(): bool {
		return true;
	}


	/**
	 * Can the schedule be configured?
	 *
	 * @return boolean
	 */
	public function hasFlexibleSchedule(): bool {
		return true;
	}


	/**
	 * Get schedule type
	 *
	 * @return int
	 */
	public function getDefaultScheduleType(): int {
		return self::SCHEDULE_TYPE_IN_HOURS;
	}


	/**
	 * Get schedule value
	 *
	 * @return int|array
	 */
	public function getDefaultScheduleValue() {
		return 1;
	}


	/**
	 * Run job
	 *
	 * @return ilCronJobResult
	 */
	public function run(): ilCronJobResult {
		$result = new ilCronJobResult();

		$result->setStatus(ilCronJobResult::STATUS_OK);

		return $result;
	}
}

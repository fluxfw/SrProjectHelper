<?php

namespace srag\Plugins\SrGitlabHelper\Job;

use Gitlab\Client;
use ilCronJob;
use ilCronJobResult;
use ilSrGitlabHelperPlugin;
use srag\ActiveRecordConfig\SrGitlabHelper\Exception\ActiveRecordConfigException;
use srag\DIC\SrGitlabHelper\DICTrait;
use srag\Plugins\SrGitlabHelper\Config\Config;
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
	const GITLAB_MAX_PER_PAGE = 100;
	const GITLAB_PAGES = 10;


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
	 *
	 * @throws ActiveRecordConfigException
	 */
	public function run(): ilCronJobResult {
		$result = new ilCronJobResult();

		$client = Client::create(Config::getField(Config::KEY_GITLAB_URL))
			->authenticate(Config::getField(Config::KEY_GITLAB_ACCESS_TOKEN), Client::AUTH_URL_TOKEN);

		$ilias_versions = array_reduce(array_filter($this->pageHelper($client, function (Client $client, array $options): array {
			return $client->repositories()->branches(Config::getField(Config::KEY_GITLAB_ILIAS_REPO_ID), $options
				+ [//"search" => "release_" // TODO: Bug, works (https://docs.gitlab.com/ee/api/branches.html), but denied by the library
				]);
		}), function (array $ilias_version): bool {
			return (strpos($ilias_version["name"], "release_") === 0 || $ilias_version["name"] === "trunk");
		}), function (array $ilias_versions, array $ilias_version): array {
			$ilias_versions[] = $ilias_version["name"];

			return $ilias_versions;
		}, []);
		rsort($ilias_versions);
		Config::setField(Config::KEY_GITLAB_ILIAS_VERSIONS, $ilias_versions);

		$plugins = array_reduce($this->pageHelper($client, function (Client $client, array $options): array {
			return $client->groups()->projects(Config::getField(Config::KEY_GITLAB_PLUGINS_GROUP_ID), $options + [
					"simple" => true
				]);
		}), function (array $plugins, array $plugin): array {
			$plugins[$plugin["name"]] = $plugin["ssh_url_to_repo"];

			return $plugins;
		}, []);
		ksort($plugins);
		Config::setField(Config::KEY_GITLAB_PLUGINS, $plugins);

		$result->setStatus(ilCronJobResult::STATUS_OK);

		return $result;
	}


	/**
	 * @param Client   $client
	 * @param callable $funcion
	 * @param int      $per_page
	 * @param int      $pages
	 *
	 * @return array
	 */
	protected function pageHelper(Client $client, callable $funcion, int $per_page = self::GITLAB_MAX_PER_PAGE, int $pages = self::GITLAB_PAGES): array {
		$result = [];

		for ($page = 1; $page <= $pages; $page ++) {
			$result = array_merge($result, $funcion($client, [
				"page" => $page,
				"per_page" => $per_page
			]));
		}

		return $result;
	}
}

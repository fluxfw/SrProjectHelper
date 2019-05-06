<?php

namespace srag\Plugins\SrGitlabHelper\Job;

use ilCachedComponentData;
use ilCronJob;
use ilCronJobResult;
use ilSrGitlabHelperPlugin;
use srag\ActiveRecordConfig\SrGitlabHelper\Exception\ActiveRecordConfigException;
use srag\DIC\SrGitlabHelper\DICTrait;
use srag\Plugins\SrGitlabHelper\Config\Config;
use srag\Plugins\SrGitlabHelper\Gitlab\Api;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;
use Throwable;

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
	 *
	 * @throws ActiveRecordConfigException
	 */
	public function run(): ilCronJobResult {
		$result = new ilCronJobResult();

		$ilias_versions = array_reduce(array_filter(Api::pageHelper(function (array $options): array {
			return self::gitlab()->repositories()->branches(Config::getField(Config::KEY_GITLAB_ILIAS_PROJECT_ID), $options
				+ [//"search" => "release_" // TODO: Bug, works (https://docs.gitlab.com/ee/api/branches.html), but denied by the library
				]);
		}), function (array $ilias_version): bool {
			return (strpos($ilias_version["name"], "release_") === 0 || $ilias_version["name"] === "trunk");
		}), function (array $ilias_versions, array $ilias_version): array {
			$ilias_versions[$ilias_version["name"]] = [
				"custom_name" => $ilias_version["name"] . "_custom",
				"develop_name" => $ilias_version["name"] . "_develop",
				"name" => $ilias_version["name"],
				"staging_name" => $ilias_version["name"] . "_staging"
			];

			return $ilias_versions;
		}, []);
		krsort($ilias_versions);
		Config::setField(Config::KEY_GITLAB_ILIAS_VERSIONS, $ilias_versions);

		$plugins = array_reduce(Api::pageHelper(function (array $options): array {
			return self::gitlab()->groups()->projects(Config::getField(Config::KEY_GITLAB_PLUGINS_GROUP_ID), $options + [
					"simple" => true
				]);
		}), function (array $plugins, array $plugin): array {
			try {
				try {
					$plugin_class = self::gitlab()->repositoryFiles()->getRawFile($plugin["id"], "classes/class.il" . $plugin["name"]
						. "Plugin.php", "master");
				} catch (Throwable $ex) {
					$plugin_class = self::gitlab()->repositoryFiles()->getRawFile($plugin["id"], "classes/class.il" . $plugin["name"]
						. "Plugin.php", "develop");
				}

				$matches = [];
				preg_match("/Plugin\s+extends\s+il([A-Za-z]+)Plugin/", $plugin_class, $matches);

				$hook = $matches[1];

				$slot = ilCachedComponentData::getInstance()->lookupPluginSlotByName($hook);

				$install_path = "Customizing/global/plugins/" . $slot["component"] . "/" . $slot["name"] . "/" . $plugin["name"];

				$plugins[$plugin["name"]] = [
					"install_path" => $install_path,
					"name" => $plugin["name"],
					"repo_http" => $plugin["http_url_to_repo"],
					"repo_ssh" => $plugin["ssh_url_to_repo"]
				];
			} catch (Throwable $ex) {
			}

			return $plugins;
		}, []);
		ksort($plugins);
		Config::setField(Config::KEY_GITLAB_PLUGINS, $plugins);

		$result->setStatus(ilCronJobResult::STATUS_OK);

		return $result;
	}
}

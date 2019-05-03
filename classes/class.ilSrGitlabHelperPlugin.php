<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\SrGitlabHelper\Util\LibraryLanguageInstaller;
use srag\Plugins\SrGitlabHelper\Config\Config;
use srag\Plugins\SrGitlabHelper\Job\Job;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;
use srag\RemovePluginDataConfirm\SrGitlabHelper\PluginUninstallTrait;

/**
 * Class ilSrGitlabHelperPlugin
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrGitlabHelperPlugin extends ilCronHookPlugin {

	use PluginUninstallTrait;
	use SrGitlabHelperTrait;
	const PLUGIN_ID = "srgitlabhelper";
	const PLUGIN_NAME = "SrGitlabHelper";
	const PLUGIN_CLASS_NAME = self::class;
	const REMOVE_PLUGIN_DATA_CONFIRM_CLASS_NAME = SrGitlabHelperRemoveDataConfirm::class;
	/**
	 * @var self|null
	 */
	protected static $instance = null;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * ilSrGitlabHelperPlugin constructor
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 * @return string
	 */
	public function getPluginName(): string {
		return self::PLUGIN_NAME;
	}


	/**
	 * @return ilCronJob[]
	 */
	public function getCronJobInstances(): array {
		return [ new Job() ];
	}


	/**
	 * @param string $a_job_id
	 *
	 * @return ilCronJob|null
	 */
	public function getCronJobInstance(/*string*/
		$a_job_id)/*: ?ilCronJob*/ {
		switch ($a_job_id) {
			case Job::CRON_JOB_ID:
				return new Job();

			default:
				return null;
		}
	}


	/**
	 * @inheritdoc
	 */
	public function updateLanguages($a_lang_keys = null) {
		parent::updateLanguages($a_lang_keys);

		LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
			. "/../vendor/srag/removeplugindataconfirm/lang")->updateLanguages();
	}


	/**
	 * @inheritdoc
	 */
	protected function deleteData()/*: void*/ {
		self::dic()->database()->dropTable(Config::TABLE_NAME, false);
	}
}

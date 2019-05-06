<?php

namespace srag\Plugins\SrGitlabHelper\Creator\GitlabClientProject;

use ilSelectInputGUI;
use srag\CustomInputGUIs\SrGitlabHelper\MultiSelectSearchInputGUI\MultiSelectSearchInputGUI;
use srag\Plugins\SrGitlabHelper\Config\Config;
use srag\Plugins\SrGitlabHelper\Creator\AbstractCreatorFormGUI;

/**
 * Class CreatorFormGUI
 *
 * @package srag\Plugins\SrGitlabHelper\Creator\GitlabClientProject
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorFormGUI extends AbstractCreatorFormGUI {

	const LANG_MODULE = CreatorGUI::LANG_MODULE;


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		parent::initFields();

		$this->fields += [
			"ilias_version" => [
				self::PROPERTY_CLASS => ilSelectInputGUI::class,
				self::PROPERTY_OPTIONS => Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS),
				self::PROPERTY_REQUIRED => true
			],
			"plugins" => [
				self::PROPERTY_CLASS => MultiSelectSearchInputGUI::class,
				self::PROPERTY_OPTIONS => array_map(function (array $plugin): string {
					return $plugin["name"];
				}, Config::getField(Config::KEY_GITLAB_PLUGINS))
			]
		];
	}
}

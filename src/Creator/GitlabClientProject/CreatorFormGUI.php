<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabClientProject;

use ilCheckboxInputGUI;
use ilSelectInputGUI;
use srag\CustomInputGUIs\SrProjectHelper\MultiSelectSearchInputGUI\MultiSelectSearchInputGUI;
use srag\Plugins\SrProjectHelper\Config\Config;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\AbstractGitlabCreatorFormGUI;

/**
 * Class CreatorFormGUI
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabClientProject
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorFormGUI extends AbstractGitlabCreatorFormGUI {

	const LANG_MODULE = CreatorGUI::LANG_MODULE;


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		parent::initFields();

		$this->fields += [
			"ilias_version" => [
				self::PROPERTY_CLASS => ilSelectInputGUI::class,
				self::PROPERTY_OPTIONS => [ "" => "" ] + array_map(function (array $ilias_version): string {
						return $ilias_version["name"];
					}, Config::getField(Config::KEY_GITLAB_ILIAS_VERSIONS)),
				self::PROPERTY_REQUIRED => true
			],
			"plugins" => [
				self::PROPERTY_CLASS => MultiSelectSearchInputGUI::class,
				self::PROPERTY_OPTIONS => array_map(function (array $plugin): string {
					return $plugin["name"];
				}, Config::getField(Config::KEY_GITLAB_PLUGINS))
			],
			"skin" => [
				self::PROPERTY_CLASS => ilCheckboxInputGUI::class
			],
			"origins" => [
				self::PROPERTY_CLASS => ilCheckboxInputGUI::class
			]
		];
	}
}

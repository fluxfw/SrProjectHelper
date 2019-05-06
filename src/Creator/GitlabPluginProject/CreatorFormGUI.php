<?php

namespace srag\Plugins\SrGitlabHelper\Creator\GitlabPluginProject;

use ilNumberInputGUI;
use srag\Plugins\SrGitlabHelper\Creator\AbstractCreatorFormGUI;

/**
 * Class CreatorFormGUI
 *
 * @package srag\Plugins\SrGitlabHelper\Creator\GitlabPluginProject
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
			"maintainer_user_id" => [
				self::PROPERTY_CLASS => ilNumberInputGUI::class,
				self::PROPERTY_REQUIRED => true
			]
		];
	}
}

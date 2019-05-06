<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject;

use srag\Plugins\SrProjectHelper\Creator\Gitlab\AbstractGitlabCreatorFormGUI;

/**
 * Class CreatorFormGUI
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject
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

		];
	}
}

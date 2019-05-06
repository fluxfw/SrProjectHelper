<?php

namespace srag\Plugins\SrGitlabHelper\Creator\GitlabPluginProject;

use srag\Plugins\SrGitlabHelper\Creator\Gitlab\AbstractGitlabCreatorFormGUI;

/**
 * Class CreatorFormGUI
 *
 * @package srag\Plugins\SrGitlabHelper\Creator\GitlabPluginProject
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

<?php

namespace srag\Plugins\SrGitlabHelper\Creator;

use ilSrGitlabHelperPlugin;
use srag\DIC\SrGitlabHelper\DICTrait;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;

/**
 * Class AbstractCreatorGUI
 *
 * @package srag\Plugins\SrGitlabHelper\Creator
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractCreatorGUI {

	use DICTrait;
	use SrGitlabHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrGitlabHelperPlugin::class;
	const CMD_CREATE = "create";
	const CMD_FORM = "form";


	/**
	 * AbstractCreatorGUI constructor
	 */
	public function __construct() {

	}


	/**
	 *
	 */
	public function executeCommand()/*: void*/ {
		if (!in_array(ilSrGitlabHelperPlugin::ADMIN_ROLE_ID, self::dic()->rbacreview()->assignedRoles(self::dic()->user()->getId()))) {
			die();
		}

		$next_class = self::dic()->ctrl()->getNextClass($this);

		switch (strtolower($next_class)) {
			default:
				$cmd = self::dic()->ctrl()->getCmd();

				switch ($cmd) {
					case self::CMD_CREATE:
					case self::CMD_FORM:
						$this->{$cmd}();
						break;

					default:
						break;
				}
				break;
		}
	}


	/**
	 *
	 */
	protected abstract function form()/*: void*/
	;


	/**
	 *
	 */
	protected abstract function create()/*: void*/
	;
}

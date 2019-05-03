<?php

namespace srag\Plugins\SrGitlabHelper\Creator;

use ilSrGitlabHelperPlugin;
use ilUtil;
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
	 * @var string
	 *
	 * @abstract
	 */
	const LANG_MODULE = "";


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
	protected function form()/*: void*/ {
		$form = $this->getCreatorForm();

		self::output()->output($form, true);
	}


	/**
	 *
	 */
	protected function create()/*: void*/ {
		$form = $this->getCreatorForm();

		if (!$form->storeForm()) {
			self::output()->output($form, true);

			return;
		}

		$data = $form->getData();

		$this->handleData($data);

		ilUtil::sendSuccess(self::plugin()->translate("created", static::LANG_MODULE),true);

		self::dic()->ctrl()->redirect($this, self::CMD_FORM);
	}


	/**
	 * @return AbstractCreatorFormGUI
	 */
	protected abstract function getCreatorForm(): AbstractCreatorFormGUI;


	/**
	 * @param array $data
	 */
	protected abstract function handleData(array $data)/*: void*/
	;
}

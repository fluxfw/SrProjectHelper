<?php

namespace srag\Plugins\SrProjectHelper\Config;

use ilMultiSelectInputGUI;
use ilNumberInputGUI;
use ilSrProjectHelperPlugin;
use ilTextInputGUI;
use srag\ActiveRecordConfig\SrProjectHelper\ActiveRecordConfigFormGUI;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\SrProjectHelper\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends ActiveRecordConfigFormGUI {

	use SrProjectHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
	const CONFIG_CLASS_NAME = Config::class;


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/ $key) {
		switch ($key) {
			default:
				return parent::getValue($key);
		}
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = [
			Config::KEY_GITLAB_URL => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			Config::KEY_GITLAB_ACCESS_TOKEN => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			Config::KEY_GITLAB_CLIENTS_GROUP_ID => [
				self::PROPERTY_CLASS => ilNumberInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			Config::KEY_GITLAB_DEPLOY_KEY_ID => [
				self::PROPERTY_CLASS => ilNumberInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			Config::KEY_GITLAB_ILIAS_PROJECT_ID => [
				self::PROPERTY_CLASS => ilNumberInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			Config::KEY_GITLAB_PLUGINS_GROUP_ID => [
				self::PROPERTY_CLASS => ilNumberInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			Config::KEY_ROLES => [
				self::PROPERTY_CLASS => ilMultiSelectInputGUI::class,
				self::PROPERTY_REQUIRED => true,
				self::PROPERTY_OPTIONS => self::ilias()->roles()->getAllRoles(),
				"enableSelectAll" => true
			]
		];
	}


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/ $key, $value)/*: void*/ {
		switch ($key) {
			case Config::KEY_ROLES:
				if ($value[0] === "") {
					array_shift($value);
				}

				$value = array_map(function (string $role_id): int {
					return intval($role_id);
				}, $value);
				break;

			default:
				break;
		}

		parent::storeValue($key, $value);
	}
}

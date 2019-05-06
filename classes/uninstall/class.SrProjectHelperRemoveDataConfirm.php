<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;
use srag\RemovePluginDataConfirm\SrProjectHelper\AbstractRemovePluginDataConfirm;

/**
 * Class SrProjectHelperRemoveDataConfirm
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy SrProjectHelperRemoveDataConfirm: ilUIPluginRouterGUI
 */
class SrProjectHelperRemoveDataConfirm extends AbstractRemovePluginDataConfirm {

	use SrProjectHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
}

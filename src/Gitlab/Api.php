<?php

namespace srag\Plugins\SrGitlabHelper\Gitlab;

use ilSrGitlabHelperPlugin;
use srag\DIC\SrGitlabHelper\DICTrait;
use srag\Plugins\SrGitlabHelper\Config\Config;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;

/**
 * Class Api
 *
 * @package srag\Plugins\SrGitlabHelper\Gitlab
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Api {

	use DICTrait;
	use SrGitlabHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrGitlabHelperPlugin::class;
	/**
	 * @var Client
	 */
	protected static $client = null;


	/**
	 * @return Client
	 */
	public static function getClient(): Client {
		if (self::$client === null) {
			self::$client = Client::create(Config::getField(Config::KEY_GITLAB_URL))
				->authenticate(Config::getField(Config::KEY_GITLAB_ACCESS_TOKEN), Client::AUTH_URL_TOKEN);
		}

		return self::$client;
	}


	/**
	 * Api constructor
	 */
	private function __construct() {

	}
}

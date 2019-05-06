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
	const GITLAB_MAX_PER_PAGE = 100;
	const GITLAB_PAGES = 10;
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
	 * @param callable $function
	 * @param int      $per_page
	 * @param int      $pages
	 *
	 * @return array
	 */
	public static function pageHelper(callable $function, int $per_page = self::GITLAB_MAX_PER_PAGE, int $pages = self::GITLAB_PAGES): array {
		$result = [];

		for ($page = 1; $page <= $pages; $page ++) {
			$result = array_merge($result, $function([
				"page" => $page,
				"per_page" => $per_page
			]));
		}

		return $result;
	}


	/**
	 * @param string $url
	 *
	 * @return string
	 */
	public static function tokenRepoUrl(string $url): string {
		// https://stackoverflow.com/questions/25409700/using-gitlab-token-to-clone-without-authentication
		return str_replace("https://", "https://gitlab-ci-token:" . Config::getField(Config::KEY_GITLAB_ACCESS_TOKEN) . "@", $url);
	}


	/**
	 * Api constructor
	 */
	private function __construct() {

	}
}

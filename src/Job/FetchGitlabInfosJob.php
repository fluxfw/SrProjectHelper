<?php

namespace srag\Plugins\SrProjectHelper\Job;

use ilCachedComponentData;
use ilCronJob;
use ilCronJobResult;
use ilSrProjectHelperPlugin;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Config\Form\FormBuilder;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;
use Throwable;

/**
 * Class FetchGitlabInfosJob
 *
 * @package srag\Plugins\SrProjectHelper\Job
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FetchGitlabInfosJob extends ilCronJob
{

    use DICTrait;
    use SrProjectHelperTrait;

    const CRON_JOB_ID = ilSrProjectHelperPlugin::PLUGIN_ID . "_fetch_gitlab_infos";
    const LANG_MODULE = "cron";
    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;


    /**
     * FetchGitlabInfosJob constructor
     */
    public function __construct()
    {

    }


    /**
     * @param array $a1
     * @param array $a2
     *
     * @return int
     */
    public static function sortHelper(array $a1, array $a2) : int
    {
        $n1 = $a1["name"];
        $n2 = $a2["name"];

        return strnatcasecmp($n1, $n2);
    }


    /**
     * @inheritDoc
     */
    public function getDefaultScheduleType() : int
    {
        return self::SCHEDULE_TYPE_DAILY;
    }


    /**
     * @inheritDoc
     */
    public function getDefaultScheduleValue() : ?int
    {
        return null;
    }


    /**
     * @inheritDoc
     */
    public function getDescription() : string
    {
        return self::plugin()->translate("fetch_gitlab_infos_description", self::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    public function getId() : string
    {
        return self::CRON_JOB_ID;
    }


    /**
     * @inheritDoc
     */
    public function getTitle() : string
    {
        return ilSrProjectHelperPlugin::PLUGIN_NAME . ": " . self::plugin()->translate("fetch_gitlab_infos", self::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    public function hasAutoActivation() : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function hasFlexibleSchedule() : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function run() : ilCronJobResult
    {
        $result = new ilCronJobResult();

        $ilias_versions = array_reduce(array_filter(self::srProjectHelper()->gitlab()->pageHelper(function (array $options) : array {
            return self::srProjectHelper()->gitlab()->client()->repositories()->branches(self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITLAB_ILIAS_PROJECT_ID), $options
                + [//"search" => "release_" // TODO: Bug, works (https://docs.gitlab.com/ee/api/branches.html), but denied by the library
                ]);
        }), function (array $ilias_version) : bool {
            return (strpos($ilias_version["name"], "release_") === 0 || $ilias_version["name"] === "trunk");
        }), function (array $ilias_versions, array $ilias_version) : array {
            $ilias_versions[$ilias_version["name"]] = [
                "custom_name"  => $ilias_version["name"] . "_custom",
                "develop_name" => $ilias_version["name"] . "_develop",
                "name"         => $ilias_version["name"],
                "staging_name" => $ilias_version["name"] . "_staging"
            ];

            return $ilias_versions;
        }, []);
        uasort($ilias_versions, [self::class, "sortHelper"]);
        self::srProjectHelper()->config()->setValue(FormBuilder::KEY_GITLAB_ILIAS_VERSIONS, $ilias_versions);

        $plugins = array_reduce(self::srProjectHelper()->gitlab()->pageHelper(function (array $options) : array {
            return self::srProjectHelper()->gitlab()->client()->groups()->projects(self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITLAB_PLUGINS_GROUP_ID), $options + [
                    "simple" => true
                ]);
        }), function (array $plugins, array $plugin) : array {
            try {
                try {
                    $plugin_class = self::srProjectHelper()->gitlab()->client()->repositoryFiles()->getRawFile($plugin["id"], "classes/class.il" . $plugin["name"]
                        . "Plugin.php", "master");
                } catch (Throwable $ex) {
                    $plugin_class = self::srProjectHelper()->gitlab()->client()->repositoryFiles()->getRawFile($plugin["id"], "classes/class.il" . $plugin["name"]
                        . "Plugin.php", "develop");
                }

                $matches = [];
                preg_match("/Plugin\s+extends\s+il([A-Za-z]+)Plugin/", $plugin_class, $matches);

                $hook = $matches[1];

                $slot = ilCachedComponentData::getInstance()->lookupPluginSlotByName($hook);

                $install_path = "Customizing/global/plugins/" . $slot["component"] . "/" . $slot["name"] . "/" . $plugin["name"];

                $plugins[$plugin["name"]] = [
                    "install_path" => $install_path,
                    "name"         => $plugin["name"],
                    "repo_http"    => $plugin["http_url_to_repo"],
                    "repo_ssh"     => $plugin["ssh_url_to_repo"]
                ];
            } catch (Throwable $ex) {
            }

            return $plugins;
        }, []);
        uasort($plugins, [self::class, "sortHelper"]);
        self::srProjectHelper()->config()->setValue(FormBuilder::KEY_GITLAB_PLUGINS, $plugins);

        $groups = array_reduce(self::srProjectHelper()->gitlab()->pageHelper(function (array $options) : array {
            return self::srProjectHelper()->gitlab()->client()->groups()->all($options);
        }), function (array $groups, array $group) : array {
            $groups[$group["id"]] = [
                "name" => $group["full_path"]
            ];

            return $groups;
        }, []);
        uasort($groups, [self::class, "sortHelper"]);
        self::srProjectHelper()->config()->setValue(FormBuilder::KEY_GITLAB_GROUPS, $groups);

        $users = array_reduce(self::srProjectHelper()->gitlab()->pageHelper(function (array $options) : array {
            return self::srProjectHelper()->gitlab()->client()->groups()->members(self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITLAB_MEMBERS_GROUP_ID), $options);
        }), function (array $users, array $member) : array {
            $user = self::srProjectHelper()->gitlab()->client()->users()->show($member["id"]);

            $users[$user["id"]] = [
                "email" => $user["email"],
                "name"  => $user["name"]
            ];

            return $users;
        }, []);
        uasort($users, [self::class, "sortHelper"]);
        self::srProjectHelper()->config()->setValue(FormBuilder::KEY_GITLAB_USERS, $users);

        $projects = array_reduce(self::srProjectHelper()->gitlab()->pageHelper(function (array $options) : array {
            return self::srProjectHelper()->gitlab()->client()->projects()->all($options + [
                    "simple" => true
                ]);
        }), function (array $projects, array $project) : array {
            $projects[$project["id"]] = [
                "name" => $project["path_with_namespace"]
            ];

            return $projects;
        }, []);
        uasort($projects, [self::class, "sortHelper"]);
        self::srProjectHelper()->config()->setValue(FormBuilder::KEY_GITLAB_PROJECTS, $projects);

        $result->setStatus(ilCronJobResult::STATUS_OK);

        return $result;
    }
}

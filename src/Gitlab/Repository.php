<?php

namespace srag\Plugins\SrProjectHelper\Gitlab;

use Gitlab\Model\Group;
use Gitlab\Model\Project;
use ilSrProjectHelperPlugin;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Config\ConfigFormGUI;
use srag\Plugins\SrProjectHelper\Gitlab\Client\Client;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrProjectHelper\Gitlab
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use SrProjectHelperTrait;
    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    const GITLAB_MAX_PER_PAGE = 100;
    const GITLAB_PAGES = 10;
    const GITLAB_OWNER_ACCESS_LEVEL = 50;
    const GITLAB_MAINTAINER_ACCESS_LEVEL = 40;
    const GITLAB_DEVELOPER_ACCESS_LEVEL = 30;
    const GITLAB_REPORTER_ACCESS_LEVEL = 20;
    const GITLAB_GUEST_ACCESS_LEVEL = 10;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @var Client
     */
    protected $client;


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @param string $temp_folder
     * @param string $url
     * @param string $path
     * @param string $name
     * @param string $relative_path
     */
    public function addSubmodule(string $temp_folder, string $url, string $path, string $name, string $relative_path)/*: void*/
    {
        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " submodule add -b master " . escapeshellarg($this->tokenRepoUrl($url)) . " "
            . escapeshellarg($path) . " 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " add . 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " commit -m " . escapeshellarg($name . " plugin submodule") . " 2>&1", $result);

        file_put_contents($temp_folder . "/.gitmodules", str_replace($this->tokenRepoUrl($url), $relative_path . "/" . $name
            . ".git", file_get_contents($temp_folder . "/.gitmodules")));

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " add . 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " commit --amend -m " . escapeshellarg($name . " plugin submodule") . " 2>&1", $result);
    }


    /**
     * @param string $temp_folder
     */
    public function cleanTempFolder(string $temp_folder)/*: void*/
    {
        $result = [];
        exec("rm -rfd " . escapeshellarg($temp_folder), $result);
    }


    /**
     * @return Client
     */
    public function client() : Client
    {
        if ($this->client === null) {
            $this->client = Client::create(self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_URL))
                ->authenticate(self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ACCESS_TOKEN), Client::AUTH_URL_TOKEN);
        }

        return $this->client;
    }


    /**
     * @param string  $temp_folder
     * @param Project $project
     * @param string  $ilias_version
     */
    public function cloneILIAS(string $temp_folder, Project $project, string $ilias_version)/*: void*/
    {
        $result = [];
        exec("git clone -b " . escapeshellarg(self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS)[$ilias_version]["develop_name"]) . " "
            . escapeshellarg($this->tokenRepoUrl($project->http_url_to_repo)) . " " . escapeshellarg($temp_folder) . " 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " remote add temp "
            . escapeshellarg($this->tokenRepoUrl((new Project(self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_PROJECT_ID),
                $this->client()))->show()->http_url_to_repo))
            . " 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " fetch temp 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " merge " . escapeshellarg("temp/" . $ilias_version) . " 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " remote remove temp 2>&1", $result);
    }


    /**
     * @param Project $project
     * @param string  $name
     * @param string  $ref
     */
    public function createBranch(Project $project, string $name, string $ref)/*: void*/
    {
        $project->createBranch($name, $ref);
    }


    /**
     * @param string $name
     * @param int    $namespace_id
     *
     * @return Group
     */
    public function createGroup(string $name, int $namespace_id) : Group
    {
        return Group::fromArray($this->client(), $this->client()->groups()->create($name, $name, "", "internal", null, null, $namespace_id));
    }


    /**
     * @param string $name
     * @param int    $namespace_id
     * @param string $default_branch
     *
     * @return Project
     */
    public function createProject(string $name, int $namespace_id, string $default_branch) : Project
    {
        return Project::create($this->client(), $name, [
            "default_branch" => $default_branch,
            "namespace_id"   => $namespace_id,
            "path"           => $name,
            "visibility"     => "internal"
        ]);
    }


    /**
     * @param string       $name
     * @param callable     $get_namespace_id
     * @param int          $maintainer_user
     * @param Project|null $project
     * @param bool         $protect_develop_branch
     *
     * @return callable[]
     */
    public function getStepsForNewPlugin(string $name, callable $get_namespace_id, int $maintainer_user,/*?*/ &$project = null, bool $protect_develop_branch = false) : array
    {
        return array_merge([
            function () use (&$name, &$get_namespace_id, &$project)/*: void*/ {
                $project = $this->createProject($name, $get_namespace_id(), "master");
            },
            function () use (&$project)/*: void*/ {
                $this->createBranch($project, "develop", "master");
            },
            function () use (&$project)/*: void*/ {
                $this->protectMasterBranch($project, "master");
            }
        ], $protect_develop_branch ? [
            function () use (&$project)/*: void*/ {
                $this->protectDevelopBranch($project, "develop");
            }
        ] : [], [
            function () use (&$project)/*: void*/ {
                $project = $this->setDefaultBranch($project, "master");
            },
            function () use (&$maintainer_user, &$project)/*: void*/ {
                $this->setMaintainer($project, $maintainer_user);
            },
            function () use (&$project)/*: void*/ {
                $this->useDeployKey($project, self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_DEPLOY_KEY_ID));
            },
            function () use (&$project)/*: void*/ {
                $this->setDisableEnableDeleteSourceBranchOptionByDefault($project);
            }
        ]);
    }


    /**
     * @param string $temp_folder
     */
    public function notIgnoreCustomizingFolder(string $temp_folder)/*: void*/
    {
        file_put_contents($temp_folder . "/.gitignore", str_replace("\n/Customizing/global", "\n#/Customizing/global", file_get_contents($temp_folder
            . "/.gitignore")));

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " add . 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " commit -m " . escapeshellarg("Not ignore Customizing/global") . " 2>&1", $result);
    }


    /**
     * @param callable $function
     * @param int      $per_page
     * @param int      $pages
     *
     * @return array
     */
    public function pageHelper(callable $function, int $per_page = self::GITLAB_MAX_PER_PAGE, int $pages = self::GITLAB_PAGES) : array
    {
        $result = [];

        for ($page = 1; $page <= $pages; $page++) {
            $result = array_merge($result, $function([
                "page"     => $page,
                "per_page" => $per_page
            ]));
        }

        return $result;
    }


    /**
     * @param Project $project
     * @param string  $branch
     */
    public function protectMasterBranch(Project $project, string $branch)/*: void*/
    {
        $this->client()->repositories()->protectBranch2($project->id, $branch, [
            "allowed_to_merge"   => true,
            "allowed_to_push"    => false,
            "merge_access_level" => self::GITLAB_MAINTAINER_ACCESS_LEVEL,
            "push_access_level"  => 0
        ]);
    }


    /**
     * @param Project $project
     * @param string  $branch
     */
    public function protectDevelopBranch(Project $project, string $branch)/*: void*/
    {
        $this->client()->repositories()->protectBranch2($project->id, $branch, [
            "allowed_to_merge"   => true,
            "allowed_to_push"    => true,
            "merge_access_level" => self::GITLAB_MAINTAINER_ACCESS_LEVEL,
            "push_access_level"  => self::GITLAB_MAINTAINER_ACCESS_LEVEL
        ]);
    }


    /**
     * @param string $temp_folder
     */
    public function push(string $temp_folder)/*: void*/
    {
        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " push 2>&1", $result);
    }


    /**
     * @param Project $project
     * @param string  $branch
     */
    public function removeBranch(Project $project, string $branch)/*: void*/
    {
        $project->branch($branch)->unprotect();

        $project->branch($branch)->delete();
    }


    /**
     * @param Project $project
     * @param string  $branch
     *
     * @return Project
     */
    public function setDefaultBranch(Project $project, string $branch) : Project
    {
        return $project->update([
            "default_branch" => $branch
        ]);
    }


    /**
     * @param Project $project
     *
     * @return Project
     */
    public function setDisableEnableDeleteSourceBranchOptionByDefault(Project $project) : Project
    {
        return $project->update([
            "remove_source_branch_after_merge" => false
        ]);
    }


    /**
     * @param Project $project
     * @param int     $maintainer_user
     */
    public function setMaintainer(Project $project, int $maintainer_user)/*: void*/
    {
        $project->addMember($maintainer_user, self::GITLAB_MAINTAINER_ACCESS_LEVEL);
    }


    /**
     * @param string $url
     *
     * @return string
     */
    protected function tokenRepoUrl(string $url) : string
    {
        // https://stackoverflow.com/questions/25409700/using-gitlab-token-to-clone-without-authentication
        return str_replace("https://", "https://gitlab-ci-token:" . self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ACCESS_TOKEN) . "@", $url);
    }


    /**
     * @param array $members
     */
    public function translateMembers(array $members)/*: void*/
    {
        return array_reduce($members, function (array $members, array $member) : array {
            if (isset($members[$member["access_level"]])) {
                $members[$member["access_level"]][] = $member["username"];
            }

            return $members;
        }, [
            self::GITLAB_OWNER_ACCESS_LEVEL      => [],
            self::GITLAB_MAINTAINER_ACCESS_LEVEL => [],
            self::GITLAB_DEVELOPER_ACCESS_LEVEL  => [],
            self::GITLAB_REPORTER_ACCESS_LEVEL   => [],
            self::GITLAB_GUEST_ACCESS_LEVEL      => []
        ]);
    }


    /**
     * @param Project $project
     * @param int     $deploy_key_id
     */
    public function useDeployKey(Project $project, int $deploy_key_id)/*: void*/
    {
        $project->enableDeployKey($deploy_key_id);
    }
}

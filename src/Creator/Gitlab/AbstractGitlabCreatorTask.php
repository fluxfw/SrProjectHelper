<?php

namespace srag\Plugins\SrProjectHelper\Creator\Gitlab;

use Gitlab\Model\Group;
use Gitlab\Model\Project;
use srag\Plugins\SrProjectHelper\Config\ConfigFormGUI;
use srag\Plugins\SrProjectHelper\Creator\AbstractCreatorTask;
use srag\Plugins\SrProjectHelper\Gitlab\Api;

/**
 * Class AbstractGitlabCreatorTaskAbstractGitlabCreatorTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator\Gitlab
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractGitlabCreatorTask extends AbstractCreatorTask
{

    /**
     * @param string $temp_folder
     * @param string $url
     * @param string $path
     * @param string $name
     * @param string $relative_path
     */
    protected function addSubmodule(string $temp_folder, string $url, string $path, string $name, string $relative_path)/*: void*/
    {
        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " submodule add -b master " . escapeshellarg(Api::tokenRepoUrl($url)) . " "
            . escapeshellarg($path) . " 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " add . 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " commit -m " . escapeshellarg($name . " plugin submodule") . " 2>&1", $result);

        file_put_contents($temp_folder . "/.gitmodules", str_replace(Api::tokenRepoUrl($url), $relative_path . "/" . $name
            . ".git", file_get_contents($temp_folder . "/.gitmodules")));

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " add . 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " commit --amend -m " . escapeshellarg($name . " plugin submodule") . " 2>&1", $result);
    }


    /**
     * @param string $temp_folder
     */
    protected function cleanTempFolder(string $temp_folder)/*: void*/
    {
        $result = [];
        exec("rm -rfd " . escapeshellarg($temp_folder), $result);
    }


    /**
     * @param string  $temp_folder
     * @param Project $project
     * @param string  $ilias_version
     */
    protected function cloneILIAS(string $temp_folder, Project $project, string $ilias_version)/*: void*/
    {
        $result = [];
        exec("git clone -b " . escapeshellarg(self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_VERSIONS)[$ilias_version]["develop_name"]) . " "
            . escapeshellarg(Api::tokenRepoUrl($project->http_url_to_repo)) . " " . escapeshellarg($temp_folder) . " 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " remote add temp "
            . escapeshellarg(Api::tokenRepoUrl((new Project(self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_ILIAS_PROJECT_ID),
                self::srProjectHelper()->gitlab()))->show()->http_url_to_repo))
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
    protected function createBranch(Project $project, string $name, string $ref)/*: void*/
    {
        $project->createBranch($name, $ref);
    }


    /**
     * @param string $name
     * @param int    $namespace_id
     *
     * @return Group
     */
    protected function createGroup(string $name, int $namespace_id) : Group
    {
        return Group::fromArray(self::srProjectHelper()->gitlab(), self::srProjectHelper()->gitlab()->groups()->create($name, $name, "", "internal", null, null, $namespace_id));
    }


    /**
     * @param string $name
     * @param int    $namespace_id
     * @param string $default_branch
     *
     * @return Project
     */
    protected function createProject(string $name, int $namespace_id, string $default_branch) : Project
    {
        return Project::create(self::srProjectHelper()->gitlab(), $name, [
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
    protected function getStepsForNewPlugin(string $name, callable $get_namespace_id, int $maintainer_user,/*?*/ &$project = null, bool $protect_develop_branch = false) : array
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
    protected function notIgnoreCustomizingFolder(string $temp_folder)/*: void*/
    {
        file_put_contents($temp_folder . "/.gitignore", str_replace("\n/Customizing/global", "\n#/Customizing/global", file_get_contents($temp_folder
            . "/.gitignore")));

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " add . 2>&1", $result);

        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " commit -m " . escapeshellarg("Not ignore Customizing/global") . " 2>&1", $result);
    }


    /**
     * @param Project $project
     * @param string  $branch
     */
    protected function protectMasterBranch(Project $project, string $branch)/*: void*/
    {
        self::srProjectHelper()->gitlab()->repositories()->protectBranch2($project->id, $branch, [
            "allowed_to_merge"   => true,
            "allowed_to_push"    => false,
            "merge_access_level" => 40,
            "push_access_level"  => 0
        ]);
    }


    /**
     * @param Project $project
     * @param string  $branch
     */
    protected function protectDevelopBranch(Project $project, string $branch)/*: void*/
    {
        self::srProjectHelper()->gitlab()->repositories()->protectBranch2($project->id, $branch, [
            "allowed_to_merge"   => true,
            "allowed_to_push"    => true,
            "merge_access_level" => 40,
            "push_access_level"  => 40
        ]);
    }


    /**
     * @param string $temp_folder
     */
    protected function push(string $temp_folder)/*: void*/
    {
        $result = [];
        exec("git -C " . escapeshellarg($temp_folder) . " push 2>&1", $result);
    }


    /**
     * @param Project $project
     * @param string  $branch
     */
    protected function removeBranch(Project $project, string $branch)/*: void*/
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
    protected function setDefaultBranch(Project $project, string $branch) : Project
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
    protected function setDisableEnableDeleteSourceBranchOptionByDefault(Project $project) : Project
    {
        return $project->update([
            "remove_source_branch_after_merge" => false
        ]);
    }


    /**
     * @param Project $project
     * @param int     $maintainer_user
     */
    protected function setMaintainer(Project $project, int $maintainer_user)/*: void*/
    {
        $project->addMember($maintainer_user, 40);
    }


    /**
     * @param Project $project
     * @param int     $deploy_key_id
     */
    protected function useDeployKey(Project $project, int $deploy_key_id)/*: void*/
    {
        $project->enableDeployKey($deploy_key_id);
    }
}

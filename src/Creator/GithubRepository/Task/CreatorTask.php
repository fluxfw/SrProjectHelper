<?php

namespace srag\Plugins\SrProjectHelper\Creator\GithubRepository\Task;

require_once __DIR__ . "/../../../../vendor/autoload.php";

use srag\Plugins\SrProjectHelper\Creator\Github\Task\AbstractGithubCreatorTask;

/**
 * Class CreatorTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GithubRepository\Task
 */
class CreatorTask extends AbstractGithubCreatorTask
{

    /**
     * @inheritDoc
     */
    protected function getOutput2() : string
    {
        return "";
    }


    /**
     * @inheritDoc
     */
    protected function getSteps(array $data) : array
    {
        return array_merge([
            function () use (&$data) : void {
                self::srProjectHelper()->github()->createRepository($data["name"]);
            }
        ], (!empty($data["project"]) ? [
            function () use (&$data) : void {
                self::srProjectHelper()->gitlab()->setGitlabGithubSync($data["project"], $data["name"]);
            }
        ] : []));
    }
}

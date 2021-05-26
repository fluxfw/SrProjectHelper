<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject\Task;

require_once __DIR__ . "/../../../../vendor/autoload.php";

use Gitlab\Model\Project;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\Task\AbstractGitlabCreatorTask;

/**
 * Class CreatorTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject\Task
 */
class CreatorTask extends AbstractGitlabCreatorTask
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
        /**
         * @var Project|null
         */
        $project = null;

        return self::srProjectHelper()->gitlab()->getStepsForNewPlugin($data["name"], function () use (&$data) : int {
            return intval($data["group"]);
        }, $data["maintainer_user"], $project);
    }
}

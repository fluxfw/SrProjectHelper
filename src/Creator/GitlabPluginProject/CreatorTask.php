<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject;

require_once __DIR__ . "/../../../vendor/autoload.php";

use Gitlab\Model\Project;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\AbstractGitlabCreatorTask;

/**
 * Class CreatorTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorTask extends AbstractGitlabCreatorTask
{

    /**
     * @inheritDoc
     */
    protected function getSteps(array $data) : array
    {
        /**
         * @var Project|null
         */
        $project = null;

        return $this->getStepsForNewPlugin($data["name"], function () use (&$data): int {
            return intval($data["group"]);
        }, $data["maintainer_user"], $project);
    }


    /**
     * @inheritDoc
     */
    protected function getOutput2() : string
    {
        return "";
    }
}

<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview;

require_once __DIR__ . "/../../../vendor/autoload.php";

use srag\Plugins\SrProjectHelper\Config\ConfigFormGUI;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\AbstractGitlabCreatorTask;
use srag\Plugins\SrProjectHelper\Gitlab\Api;
use srag\Plugins\SrProjectHelper\Job\FetchGitlabInfosJob;

/**
 * Class CreatorTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorTask extends AbstractGitlabCreatorTask
{

    /**
     * @var string
     */
    protected $csv;


    /**
     * @inheritDoc
     */
    protected function getSteps(array $data) : array
    {
        return [
            function ()/*: void*/ {
                $data = [];

                foreach (self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_PROJECTS) as $id => $project) {

                    $project += Api::translateMembers(self::srProjectHelper()->gitlab()->projects()->members($id));
                    $data[] = $project;
                }

                foreach (self::srProjectHelper()->config()->getValue(ConfigFormGUI::KEY_GITLAB_GROUPS) as $id => $group) {
                    $group += Api::translateMembers(self::srProjectHelper()->gitlab()->groups()->members($id));
                    $data[] = $group;
                }

                uasort($data, [FetchGitlabInfosJob::class, "sortHelper"]);

                $this->csv = $this->csv(["path", "owners", "maintainers", "developers", "reporters", "guests"], $data);
            }
        ];
    }


    /**
     * @inheritDoc
     */
    protected function getOutput2() : string
    {
        return $this->csv;
    }
}

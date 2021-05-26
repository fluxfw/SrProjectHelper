<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview\Task;

require_once __DIR__ . "/../../../../vendor/autoload.php";

use srag\Plugins\SrProjectHelper\Config\Form\FormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\Task\AbstractGitlabCreatorTask;
use srag\Plugins\SrProjectHelper\Job\FetchGitlabInfosJob;

/**
 * Class CreatorTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview\Task
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
    protected function getOutput2() : string
    {
        return $this->csv;
    }


    /**
     * @inheritDoc
     */
    protected function getSteps(array $data) : array
    {
        return [
            function () : void {
                $data = [];

                foreach (self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITLAB_PROJECTS) as $id => $project) {

                    $project += self::srProjectHelper()->gitlab()->translateMembers(self::srProjectHelper()->gitlab()->client()->projects()->members($id));
                    $data[] = $project;
                }

                foreach (self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITLAB_GROUPS) as $id => $group) {
                    $group += self::srProjectHelper()->gitlab()->translateMembers(self::srProjectHelper()->gitlab()->client()->groups()->members($id));
                    $data[] = $group;
                }

                uasort($data, [FetchGitlabInfosJob::class, "sortHelper"]);

                $this->csv = $this->csv(["path", "owners", "maintainers", "developers", "reporters", "guests"], $data);
            }
        ];
    }
}

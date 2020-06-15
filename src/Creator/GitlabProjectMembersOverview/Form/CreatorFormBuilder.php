<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview\Form;

use srag\Plugins\SrProjectHelper\Creator\Gitlab\Form\AbstractGitlabCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview\CreatorGUI;

/**
 * Class CreatorFormBuilder
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabProjectMembersOverview\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorFormBuilder extends AbstractGitlabCreatorFormBuilder
{

    /**
     * @inheritDoc
     *
     * @param CreatorGUI $parent
     */
    public function __construct(CreatorGUI $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    public function getData2() : array
    {
        $data = [
            "name" => self::plugin()->translate("task_title", $this->parent::LANG_MODULE)
        ];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [];

        return $fields;
    }
}

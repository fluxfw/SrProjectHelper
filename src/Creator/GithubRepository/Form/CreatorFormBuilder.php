<?php

namespace srag\Plugins\SrProjectHelper\Creator\GithubRepository\Form;

use srag\Plugins\SrProjectHelper\Config\Form\FormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Github\Form\AbstractGithubCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\GithubRepository\CreatorGUI;

/**
 * Class CreatorFormBuilder
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GithubRepository\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorFormBuilder extends AbstractGithubCreatorFormBuilder
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
    protected function getFields() : array
    {
        $this->messages[] = self::dic()->ui()->factory()->messageBox()->info(nl2br(self::plugin()->translate("manual_trigger_remote_mirror_needed", $this->parent::LANG_MODULE), false));

        $fields = parent::getFields();

        $fields += [
            "project" => self::dic()->ui()->factory()->input()->field()->select(self::plugin()->translate("project", $this->parent::LANG_MODULE),
                ["" => ""] + array_map(function (array $project) : string {
                    return $project["name"];
                }, self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITLAB_PROJECTS)))->withRequired(true)
        ];

        return $fields;
    }
}

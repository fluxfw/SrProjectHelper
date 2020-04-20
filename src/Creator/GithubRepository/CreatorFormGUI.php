<?php

namespace srag\Plugins\SrProjectHelper\Creator\GithubRepository;

use ilSelectInputGUI;
use srag\Plugins\SrProjectHelper\Config\Form\FormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Github\AbstractGithubCreatorFormGUI;

/**
 * Class CreatorFormGUI
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GithubRepository
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatorFormGUI extends AbstractGithubCreatorFormGUI
{

    const LANG_MODULE = CreatorGUI::LANG_MODULE;


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        parent::initFields();

        $this->fields += [
            "project" => [
                self::PROPERTY_CLASS   => ilSelectInputGUI::class,
                self::PROPERTY_OPTIONS => ["" => ""] + array_map(function (array $project) : string {
                        return $project["name"];
                    }, self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITLAB_PROJECTS))
            ]
        ];
    }
}

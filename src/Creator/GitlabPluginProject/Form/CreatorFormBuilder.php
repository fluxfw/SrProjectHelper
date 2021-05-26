<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject\Form;

use srag\Plugins\SrProjectHelper\Config\Form\FormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\Form\AbstractGitlabCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject\CreatorGUI;

/**
 * Class CreatorFormBuilder
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabPluginProject\Form
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
    protected function getFields() : array
    {
        $fields = parent::getFields();

        $fields += [
            "group" => self::dic()->ui()->factory()->input()->field()->select(self::plugin()->translate("group", $this->parent::LANG_MODULE),
                ["" => ""] + array_map(function (array $group) : string {
                    return $group["name"];
                }, self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITLAB_GROUPS)))->withRequired(true)
        ];

        return $fields;
    }
}

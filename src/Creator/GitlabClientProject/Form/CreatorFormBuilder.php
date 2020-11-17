<?php

namespace srag\Plugins\SrProjectHelper\Creator\GitlabClientProject\Form;

use srag\CustomInputGUIs\SrProjectHelper\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\SrProjectHelper\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use srag\Plugins\SrProjectHelper\Config\Form\FormBuilder;
use srag\Plugins\SrProjectHelper\Creator\Gitlab\Form\AbstractGitlabCreatorFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\GitlabClientProject\CreatorGUI;

/**
 * Class CreatorFormBuilder
 *
 * @package srag\Plugins\SrProjectHelper\Creator\GitlabClientProject\Form
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
    protected function getFields() : array
    {
        $fields = parent::getFields();

        $fields += [
            "ilias_version" => self::dic()->ui()->factory()->input()->field()->select(self::plugin()->translate("ilias_version", $this->parent::LANG_MODULE),
                ["" => ""] + array_map(function (array $ilias_version) : string {
                    return $ilias_version["name"];
                }, self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITLAB_ILIAS_VERSIONS)))->withRequired(true),
            "plugins"       => (new InputGUIWrapperUIInputComponent(new MultiSelectSearchNewInputGUI(self::plugin()->translate("plugins", $this->parent::LANG_MODULE)))),
            "skin"          => self::dic()->ui()->factory()->input()->field()->checkbox(self::plugin()->translate("skin", $this->parent::LANG_MODULE)),
            "origins"       => self::dic()->ui()->factory()->input()->field()->checkbox(self::plugin()->translate("origins", $this->parent::LANG_MODULE))
        ];
        $fields["plugins"]->getInput()->setOptions(array_map(function (array $plugin) : string {
            return $plugin["name"];
        }, self::srProjectHelper()->config()->getValue(FormBuilder::KEY_GITLAB_PLUGINS)));

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data) : void
    {
        $data["plugins"] = MultiSelectSearchNewInputGUI::cleanValues((array) $data["plugins"]);

        parent::storeData($data);
    }
}

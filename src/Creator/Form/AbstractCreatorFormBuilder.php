<?php

namespace srag\Plugins\SrProjectHelper\Creator\Form;

use ilSrProjectHelperPlugin;
use srag\CustomInputGUIs\SrProjectHelper\FormBuilder\AbstractFormBuilder;
use srag\Plugins\SrProjectHelper\Creator\AbstractCreatorGUI;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class AbstractCreatorFormBuilder
 *
 * @package srag\Plugins\SrProjectHelper\Creator\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractCreatorFormBuilder extends AbstractFormBuilder
{

    use SrProjectHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    /**
     * @var array
     */
    protected $data = [];


    /**
     * @inheritDoc
     *
     * @param AbstractCreatorGUI $parent
     */
    public function __construct(AbstractCreatorGUI $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @return array
     */
    public function getData2() : array
    {
        return $this->data;
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [
            AbstractCreatorGUI::CMD_CREATE => self::plugin()->translate("create", $this->parent::LANG_MODULE)
        ];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = $this->data;

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [
            "name" => self::dic()->ui()->factory()->input()->field()->text(self::plugin()->translate("name", $this->parent::LANG_MODULE))->withRequired(true)
        ];

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return self::plugin()->translate("title", $this->parent::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data)/*:void*/
    {
        $this->data = $data;
    }
}

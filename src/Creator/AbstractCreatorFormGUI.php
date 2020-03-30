<?php

namespace srag\Plugins\SrProjectHelper\Creator;

use ilSrProjectHelperPlugin;
use ilTextInputGUI;
use srag\CustomInputGUIs\SrProjectHelper\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class AbstractCreatorFormGUI
 *
 * @package srag\Plugins\SrProjectHelper\Creator
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractCreatorFormGUI extends PropertyFormGUI
{

    use SrProjectHelperTrait;
    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    /**
     * @var array
     */
    protected $data = [];


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }
                break;
        }

        return null;
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(AbstractCreatorGUI::CMD_CREATE, $this->txt("create"));
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            "name" => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {
        $this->setId(ilSrProjectHelperPlugin::PLUGIN_ID . "_form");
    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("title"));
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch ($key) {
            default:
                $this->data[$key] = $value;
                break;
        }
    }


    /**
     * @return array
     */
    public function getData() : array
    {
        return $this->data;
    }
}

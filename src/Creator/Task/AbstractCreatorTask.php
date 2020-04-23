<?php

namespace srag\Plugins\SrProjectHelper\Creator\Task;

use ILIAS\BackgroundTasks\Implementation\Tasks\AbstractJob;
use ILIAS\BackgroundTasks\Implementation\Values\ScalarValues\StringValue;
use ILIAS\BackgroundTasks\Observer;
use ILIAS\BackgroundTasks\Types\SingleType;
use ILIAS\BackgroundTasks\Types\Type;
use ILIAS\BackgroundTasks\Value;
use ilSrProjectHelperPlugin;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class AbstractCreatorTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator\Task
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractCreatorTask extends AbstractJob
{

    use DICTrait;
    use SrProjectHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;


    /**
     * @inheritDoc
     */
    public function isStateless() : bool
    {
        return false;
    }


    /**
     * @inheritDoc
     */
    public function getExpectedTimeOfTaskInSeconds() : int
    {
        return 300;
    }


    /**
     * @inheritDoc
     */
    public function getInputTypes() : array
    {
        return [
            new SingleType(StringValue::class)
        ];
    }


    /**
     * @inheritDoc
     */
    public function getOutputType() : Type
    {
        return new SingleType(StringValue::class);
    }


    /**
     * @inheritDoc
     */
    public function run(array $input, Observer $observer) : Value
    {
        $output = new StringValue();

        $data = json_decode($input[0]->getValue(), true);

        $steps = $this->getSteps($data);

        foreach ($steps as $i => $step) {
            $step();

            $observer->notifyPercentage($this, intval(($i + 1) / count($steps) * 100));
        }

        $output->setValue($this->getOutput2());

        return $output;
    }


    /**
     * @param array $header
     * @param array $rows
     *
     * @return string
     */
    protected function csv(array $header, array $rows) : string
    {
        return implode("\n", array_map(function ($columns) : string {
            if (is_array($columns)) {
                return implode(";", array_map(function ($column) : string {
                    if (is_array($column)) {
                        return '"' . implode("\n", $column) . '"';
                    } else {
                        return '"' . strval($column) . '"';
                    }
                }, $columns));
            } else {
                return '"' . strval($columns) . '"';
            }
        }, array_merge([$header], $rows)));
    }


    /**
     * @param array $data
     *
     * @return callable[]
     */
    protected abstract function getSteps(array $data) : array;


    /**
     * @return string
     */
    protected abstract function getOutput2() : string;
}

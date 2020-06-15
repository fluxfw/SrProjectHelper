<?php

namespace srag\Plugins\SrProjectHelper\Creator\Task;

require_once __DIR__ . "/../../../vendor/autoload.php";

use ILIAS\BackgroundTasks\Bucket;
use ILIAS\BackgroundTasks\Implementation\Tasks\AbstractUserInteraction;
use ILIAS\BackgroundTasks\Implementation\Tasks\UserInteraction\UserInteractionOption;
use ILIAS\BackgroundTasks\Implementation\Values\ScalarValues\StringValue;
use ILIAS\BackgroundTasks\Task\UserInteraction\Option;
use ILIAS\BackgroundTasks\Types\SingleType;
use ILIAS\BackgroundTasks\Types\Type;
use ilSrProjectHelperPlugin;
use ilUtil;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class DownloadOutputTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator\Task
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DownloadOutputTask extends AbstractUserInteraction
{

    use DICTrait;
    use SrProjectHelperTrait;

    const CMD_DOWNLOAD = "download";
    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;


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
    public function getOptions(array $input)
    {
        return [
            new UserInteractionOption("download", self::CMD_DOWNLOAD),
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
    public function interaction(array $input, Option $user_selected_option, Bucket $bucket)
    {
        switch ($user_selected_option->getValue()) {
            case self::CMD_DOWNLOAD:
                $filename = "members_of_project_" . time() . ".csv";

                $data = $input[0]->getValue();

                ilUtil::deliverData($data, $filename);
                break;

            default:
                break;
        }

        return $input;
    }
}

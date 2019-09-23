<?php

namespace srag\Plugins\SrProjectHelper\Creator;

// BackgroundTasks Bug
require_once __DIR__ . "/../../vendor/autoload.php";

use GuzzleHttp\Psr7\Stream;
use ILIAS\BackgroundTasks\Bucket;
use ILIAS\BackgroundTasks\Implementation\Tasks\AbstractUserInteraction;
use ILIAS\BackgroundTasks\Implementation\Tasks\UserInteraction\UserInteractionOption;
use ILIAS\BackgroundTasks\Implementation\Values\ScalarValues\StringValue;
use ILIAS\BackgroundTasks\Task\UserInteraction\Option;
use ILIAS\BackgroundTasks\Types\SingleType;
use ILIAS\BackgroundTasks\Types\Type;
use ilMimeTypeUtil;
use ilSrProjectHelperPlugin;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class DownloadOutputTask
 *
 * @package srag\Plugins\SrProjectHelper\Creator
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DownloadOutputTask extends AbstractUserInteraction
{

    use DICTrait;
    use SrProjectHelperTrait;
    const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
    const CMD_DOWNLOAD = "download";


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
    public function getOptions(array $input)
    {
        return [
            new UserInteractionOption("download", self::CMD_DOWNLOAD),
        ];
    }


    /**
     * @inheritDoc
     */
    public function interaction(array $input, Option $user_selected_option, Bucket $bucket)
    {
        switch ($user_selected_option->getValue()) {
            case self::CMD_DOWNLOAD:
                $filename = "members_of_project_" . time() . ".csv";

                $stream = new Stream(fopen("php://memory", "rw"));
                $stream->write($input[0]->getValue());

                self::dic()->http()->saveResponse(self::dic()->http()->response()->withBody($stream)->withHeader("Content-Disposition", 'attachment; filename="'
                    . $filename . '"')// Filename
                ->withHeader("Content-Type", ilMimeTypeUtil::APPLICATION__OCTET_STREAM)// Force download
                ->withHeader("Expires", "0")->withHeader("Pragma", "public"));// No cache

                self::dic()->http()->sendResponse();

                exit;
                break;

            default:
                break;
        }

        return $input;
    }
}

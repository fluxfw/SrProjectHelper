<?php

require_once __DIR__ . "/../vendor/autoload.php";

use ILIAS\DI\Container;
use ILIAS\GlobalScreen\Provider\PluginProviderCollection;
use srag\CustomInputGUIs\SrProjectHelper\Loader\CustomInputGUIsLoaderDetector;
use srag\DevTools\SrProjectHelper\DevToolsCtrl;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;
use srag\RemovePluginDataConfirm\SrProjectHelper\PluginUninstallTrait;

/**
 * Class ilSrProjectHelperPlugin
 */
class ilSrProjectHelperPlugin extends ilCronHookPlugin
{

    use PluginUninstallTrait;
    use SrProjectHelperTrait;

    const PLUGIN_CLASS_NAME = self::class;
    const PLUGIN_ID = "srprojecthelper";
    const PLUGIN_NAME = "SrProjectHelper";
    /**
     * @var self|null
     */
    protected static $instance = null;
    /**
     * @var PluginProviderCollection|null
     */
    protected static $pluginProviderCollection = null;


    /**
     * ilSrProjectHelperPlugin constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->provider_collection = self::getPluginProviderCollection(); // Fix overflow
    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @return PluginProviderCollection
     */
    protected static function getPluginProviderCollection() : PluginProviderCollection
    {
        if (self::$pluginProviderCollection === null) {
            self::$pluginProviderCollection = new PluginProviderCollection();

            self::$pluginProviderCollection->setMainBarProvider(self::srProjectHelper()->menu());
        }

        return self::$pluginProviderCollection;
    }


    /**
     * @inheritDoc
     */
    public function exchangeUIRendererAfterInitialization(Container $dic) : Closure
    {
        return CustomInputGUIsLoaderDetector::exchangeUIRendererAfterInitialization();
    }


    /**
     * @inheritDoc
     */
    public function getCronJobInstance(/*string*/ $a_job_id) : ?ilCronJob
    {
        return self::srProjectHelper()->jobs()->factory()->newInstanceById($a_job_id);
    }


    /**
     * @inheritDoc
     */
    public function getCronJobInstances() : array
    {
        return self::srProjectHelper()->jobs()->factory()->newInstances();
    }


    /**
     * @inheritDoc
     */
    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }


    /**
     * @inheritDoc
     */
    public function updateLanguages(/*?array*/ $a_lang_keys = null) : void
    {
        parent::updateLanguages($a_lang_keys);

        $this->installRemovePluginDataConfirmLanguages();

        DevToolsCtrl::installLanguages(self::plugin());
    }


    /**
     * @inheritDoc
     */
    protected function deleteData() : void
    {
        self::srProjectHelper()->dropTables();
    }


    /**
     * @inheritDoc
     */
    protected function shouldUseOneUpdateStepOnly() : bool
    {
        return true;
    }
}

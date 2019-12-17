<?php

require_once __DIR__ . "/../vendor/autoload.php";

use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use srag\DIC\SrProjectHelper\Util\LibraryLanguageInstaller;
use srag\Plugins\SrProjectHelper\Job\FetchGitlabInfosJob;
use srag\Plugins\SrProjectHelper\Menu\Menu;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;
use srag\RemovePluginDataConfirm\SrProjectHelper\PluginUninstallTrait;

/**
 * Class ilSrProjectHelperPlugin
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrProjectHelperPlugin extends ilCronHookPlugin
{

    use PluginUninstallTrait;
    use SrProjectHelperTrait;
    const PLUGIN_ID = "srprojecthelper";
    const PLUGIN_NAME = "SrProjectHelper";
    const PLUGIN_CLASS_NAME = self::class;
    const REMOVE_PLUGIN_DATA_CONFIRM_CLASS_NAME = SrProjectHelperRemoveDataConfirm::class;
    const ADMIN_ROLE_ID = 2;
    /**
     * @var self|null
     */
    protected static $instance = null;


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
     * ilSrProjectHelperPlugin constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return string
     */
    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }


    /**
     * @return ilCronJob[]
     */
    public function getCronJobInstances() : array
    {
        return [new FetchGitlabInfosJob()];
    }


    /**
     * @param string $a_job_id
     *
     * @return ilCronJob|null
     */
    public function getCronJobInstance(/*string*/ $a_job_id)/*: ?ilCronJob*/
    {
        switch ($a_job_id) {
            case FetchGitlabInfosJob::CRON_JOB_ID:
                return new FetchGitlabInfosJob();

            default:
                return null;
        }
    }


    /**
     * @inheritdoc
     */
    public function promoteGlobalScreenProvider() : AbstractStaticPluginMainMenuProvider
    {
        return new Menu(self::dic()->dic(), $this);
    }


    /**
     * @inheritdoc
     */
    public function updateLanguages($a_lang_keys = null)
    {
        parent::updateLanguages($a_lang_keys);

        LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
            . "/../vendor/srag/removeplugindataconfirm/lang")->updateLanguages();
    }


    /**
     * @inheritdoc
     */
    protected function deleteData()/*: void*/
    {
        self::srProjectHelper()->dropTables();
    }
}

<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;

class gabrielyan_comments extends CModule
{
    public function __construct() {
        $arModuleVersion = [];
        include __DIR__ . "/version.php";

        $this->MODULE_ID = "gabrielyan.comments";
        $this->MODULE_NAME = Loc::getMessage("MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("MODULE_DESCRIPTION");

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->PARTNER_NAME = Loc::getMessage("PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("PARTNER_URI");
    }

    public function DoInstall()
    {
        if($this->isVersionD7()) {
            ModuleManager::registerModule($this->MODULE_ID);
            $this->InstallDB();
            $this->InstallFiles();
        } else {
            CAdminMessage::ShowMessage(
                array(
                    "TYPE" => "ERROR",
                    "MESSAGE" => Loc::getMessage("ERROR_VERSION_D7_REQUIRED"),
                    "DETAILS" => Loc::getMessage("ERROR_VERSION_D7_REQUIRED_DETAIL"),
                    "HTML" => true
                )
            );
        }
    }

    public function DoUninstall()
    {
        $this->UnInstallFiles();
        $this->UnInstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallDB()
    {
        if(\Bitrix\Main\Loader::includeModule($this->MODULE_ID)) {
            $entity = \Gabrielyan\Comments\Models\CommentsTable::getEntity();
            $connection = $entity->getConnection();

            if(!$connection->isTableExists($entity->getDBTableName())) {
                $entity->createDbTable();
            }
        }
    }

    public function UnInstallDB()
    {
        \Bitrix\Main\Loader::includeModule($this->MODULE_ID);
        if (\Bitrix\Main\Loader::includeModule($this->MODULE_ID)) {
            $entity = \Gabrielyan\Comments\Models\CommentsTable::getEntity();
            $connection = $entity->getConnection();

            if ($connection->isTableExists($entity->getDBTableName())) {
                $connection->dropTable($entity->getDBTableName());
            }
        } else {
            throw new \Bitrix\Main\SystemException("Module {$this->MODULE_ID} is not installed.");
        }
    }

    public function InstallFiles($arParams = [])
    {
        $modulePath = $this->GetPath(true);

        CopyDirFiles(
            $modulePath . '/install/components',
            Application::getDocumentRoot() . '/local/components',
            true,
            true
        );

        return true;
    }

    public function UnInstallFiles()
    {
        $modulePath = $this->GetPath(true);

        DeleteDirFiles(
            $modulePath . '/install/components',
            Application::getDocumentRoot() . '/local/components'
        );

        return true;
    }

    public function GetPath($notDocumentRoot = false)
    {
        return $notDocumentRoot
            ? str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__))
            : dirname(__DIR__);
    }

    public function isVersionD7()
    {
        return CheckVersion(ModuleManager::getVersion('main'), '18.00.00');
    }

}
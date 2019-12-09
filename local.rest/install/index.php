<?php

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

class LOCAL_rest extends CModule
{
    var $MODULE_ID = 'local.rest';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;
    var $MODULE_CSS;

    public function local_rest()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_NAME = Loc::getMessage('LOCAL_MODULE_REST_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('LOCAL_MODULE_REST_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('LOCAL_MODULE_REST_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('LOCAL_MODULE_REST_PARTNER_WEBSITE');
    }

    public function DoInstall()
    {
        $this->InstallFiles();
        RegisterModule($this->MODULE_ID);
        
        /*
        --------------- В init.php вставить
        
        $eventManager->addEventHandler('local.rest', 'onRestMethodBuildDescription', 'restServiceDescription');

        function restServiceDescription()
        {
            return [
                'data.get' => [
                    'allow_methods' => [],
                    'callback' => ['\\Local\\Rest\\Rest2', 'dataGet']
                ],
                'data.update' => [
                    'allow_methods' => ['POST'],
                    'authenticator' => ['\\Rest1', 'isAuthorized'],
                    'callback' => ['\\Rest1', 'dataUpdate']
                ],
            ];
        }
        
        --------------- Разместить 
        
        \\Local\\Rest\\Rest2
        \bitrix\modules\local.rest\lib\rest2.php
    
        */
        
        
        
        
        return true;
    }

    public function InstallFiles()
    {
        $site = Application::getInstance()->getContext()->getSite();
        // $site=='NULL';
        $site='s1';
        $urls = \Bitrix\Main\UrlRewriter::getList($site);
        $cnturls=count($urls);
        while ($cnturls--)
            {
                $urls[$cnturls]['ID']=='local.rest.services'&&$restisset=1;
            }
            if (gettype($restisset)=='NULL')
            {
                CUrlRewriter::Add(array(
                    'CONDITION' => '#^/rest/#',
                    'RULE' => '',
                    'ID' => 'local.rest.services',
                    'PATH' => '/bitrix/services/local.rest/index.php',
                ));	
            }else{
                CUrlRewriter::Update(array(
                    // "SITE_ID" => SITE_ID,
                    // "CONDITION" => '#^/rest/#',
                    "ID" => 'local.rest.services',
                ), array(
                    'ID' => 'local.rest.services',
                    'CONDITION' => '#^/rest/#',
                    "PATH" => '/bitrix/services/local.rest/index.php',
                ));
            }

        CopyDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . $this->MODULE_ID . '/install/components/local',
            Application::getDocumentRoot() . '/bitrix/components/local', true, true);
        CopyDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . $this->MODULE_ID . '/install/services/local.rest',
            Application::getDocumentRoot() . '/bitrix/services/local.rest', true, true);
    }

    public function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);
       /* \Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler(
            'rest',
            'OnRestServiceBuildDescription',
            'local.rest',
            // '\Bizprofi\Randomizer\Rest\Service',
            'getDescription'
        );
        */
        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . $this->MODULE_ID . '/install/components/local',
            Application::getDocumentRoot() . '/bitrix/components/local');
        DeleteDirFiles(Application::getDocumentRoot() . '/bitrix/modules/' . $this->MODULE_ID . '/install/services/local.rest',
            Application::getDocumentRoot() . '/bitrix/services/local.rest');
        CUrlRewriter::Delete(array(
            'ID' => 'local.rest.services',
            'PATH' => '/bitrix/services/local.rest/index.php',
        ));
    }
}
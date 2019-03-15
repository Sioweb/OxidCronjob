<?php

namespace Sioweb\Oxid\Cronjob\Core\Module;

use OxidEsales\Eshop\Core\Module\Module as EshopModule;
use Sioweb\Oxid\Cronjob\Core\Routing\Module\CronjobClassProviderStorage;

class ModuleInstaller extends ModuleInstaller_parent
{
    
    /**
     * Activate extension by merging module class inheritance information with shop module array
     *
     * @param EshopModule $module
     *
     * @return bool
     */
    public function activate(EshopModule $module)
    {
        if ($moduleId = $module->getId()) {
            $this->addModuleCronjobClasses($module->getInfo("cronjob"), $moduleId);
        }
        return parent::activate($module);
    }

    /**
     * Add cronjobs map for a given module Id to config
     *
     * @param array  $moduleForms Map of cronjob ids and class names
     * @param string $moduleId          The Id of the module
     */
    protected function addModuleCronjobClasses($moduleForms, $moduleId)
    {
        $this->validateModuleMetadataCronjobOnActivation($moduleForms);

        $classProviderStorage = $this->getCronjobProviderStorage();

        $classProviderStorage->add($moduleId, $moduleForms);
    }
    

    /**
     * Ensure integrity of the cronjobMap before storing it.
     * Both keys and values must be unique with in the same shop or sub-shop.
     *
     * @param array $moduleForms
     *
     * @throws ModuleValidationException
     */
    protected function validateModuleMetadataCronjobOnActivation($moduleForms)
    {
        $moduleCronjobMapProvider = $this->getModuleCronjobMapProvider();
        $shopControllerMapProvider = $this->getShopControllerMapProvider();

        $moduleCronjobMap = $moduleCronjobMapProvider->getCronjobMap();
        $shopControllerMap = $shopControllerMapProvider->getControllerMap();

        $existingMaps = array_merge($moduleCronjobMap, $shopControllerMap);
        return;
        /**
         * Ensure, that cronjob keys are unique.
         * As keys are always stored in lower case, we must test against lower case keys here as well
         */
        $duplicatedKeys = array_intersect_key(array_change_key_case($moduleForms, CASE_LOWER), $existingMaps);
        if (!empty($duplicatedKeys)) {
            throw new \OxidEsales\Eshop\Core\Exception\ModuleValidationException(implode(',', $duplicatedKeys));
        }

        /**
         * Ensure, that cronjob values are unique.
         */
        $duplicatedValues = array_intersect($moduleForms, $existingMaps);
        if (!empty($duplicatedValues)) {
            throw new \OxidEsales\Eshop\Core\Exception\ModuleValidationException(implode(',', $duplicatedValues));
        }
    }

    /**
     * @return \Sioweb\Oxid\Cronjob\Core\Contract\CronjobMapProviderInterface
     */
    protected function getModuleCronjobMapProvider()
    {
        return oxNew(\Sioweb\Oxid\Cronjob\Core\Routing\ModuleCronjobMapProvider::class);
    }

    /**
     * @return object
     */
    protected function getCronjobProviderStorage()
    {
        $classProviderStorage = oxNew(CronjobClassProviderStorage::class);

        return $classProviderStorage;
    }

}

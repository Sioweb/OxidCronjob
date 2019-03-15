<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace Sioweb\Oxid\Cronjob\Core\Routing\Module;

use OxidEsales\Eshop\Core\Contract\ClassProviderStorageInterface;
use OxidEsales\Eshop\Core\Registry;

class CronjobClassProviderStorage implements ClassProviderStorageInterface
{
    /**
     * @var string The key under which the value will be stored.
     */
    const STORAGE_KEY = 'aModuleCronjob';

    /**
     * Get the stored cronjob value from the oxconfig.
     *
     * @return null|array The cronjobs field of the modules metadata.
     */
    public function get()
    {
        return (array) $this->getConfig()->getShopConfVar(self::STORAGE_KEY);
    }

    /**
     * Set the stored cronjob value from the oxconfig.
     *
     * @param array $value The cronjobs field of the modules metadata.
     */
    public function set($value)
    {
        $value = $this->toLowercase($value);
        $this->getConfig()->saveShopConfVar('aarr', self::STORAGE_KEY, $value);
    }

    /**
     * Add the cronjobs for the module, given by its ID, to the storage.
     *
     * @param string $moduleId    The ID of the module cronjobs to add.
     * @param array  $cronjobs The cronjobs to add to the storage.
     */
    public function add($moduleId, $cronjobs)
    {
        $cronjobMap = $this->get();
        $cronjobMap[$moduleId] = $cronjobs;

        $this->set($cronjobMap);
    }

    /**
     * Delete the cronjobs for the module, given by its ID, from the storage.
     *
     * @param string $moduleId The ID of the module, for which we want to delete the cronjobs from the storage.
     */
    public function remove($moduleId)
    {
        $cronjobMap = $this->get();
        unset($cronjobMap[strtolower($moduleId)]);

        $this->set($cronjobMap);
    }

    /**
     * Change the module IDs and the cronjob keys to lower case.
     *
     * @param array $modulesCronjobs The cronjob arrays of several modules.
     *
     * @return array The given cronjob arrays of several modules, with the module IDs and the cronjob keys in lower case.
     */
    private function toLowercase($modulesCronjobs)
    {
        $result = [];

        if (!is_null($modulesCronjobs)) {
            foreach ($modulesCronjobs as $moduleId => $cronjobs) {
                $result[strtolower($moduleId)] = $this->cronjobKeysToLowercase($cronjobs);
            }
        }

        return $result;
    }

    /**
     * Change the cronjob keys to lower case.
     *
     * @param array $cronjobs The cronjobs array of one module.
     *
     * @return array The given cronjobs array with the cronjob keys in lower case.
     */
    private function cronjobKeysToLowercase($cronjobs)
    {
        $result = [];

        foreach ($cronjobs as $cronjobKey => $cronjobClass) {
            $result[strtolower($cronjobKey)] = $cronjobClass;
        }

        return $result;
    }

    /**
     * Get the config object.
     *
     * @return \oxConfig The config object.
     */
    private function getConfig()
    {
        return Registry::getConfig();
    }
}

<?php

namespace Sioweb\Oxid\Cronjob\Core\Routing;

use Sioweb\Oxid\Cronjob\Core\Routing\Module\CronjobClassProviderStorage;
use Sioweb\Oxid\Cronjob\Core\Contract\CronjobMapProviderInterface;
use OxidEsales\Eshop\Core\Registry;

/**
 * Provide the cronjob mappings from the metadata of all active modules.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://wiki.oxidforge.org/Tutorials/Core_OXID_eShop_classes:_must_not_be_extended
 */
class ModuleCronjobMapProvider implements CronjobMapProviderInterface
{
    /**
     * Get the cronjob map of the modules.
     *
     * Returns an associative array, where
     *  - the keys are the cronjob ids
     *  - the values are the routed class names
     *
     * @return array
     */
    public function getCronjobMap()
    {
        $cronjobMap = [];
        $moduleCronjobsByModuleId = Registry::getUtilsObject()->getModuleVar(CronjobClassProviderStorage::STORAGE_KEY);

        if (is_array($moduleCronjobsByModuleId)) {
            $cronjobMap = $this->flattenCronjobsMap($moduleCronjobsByModuleId);
        }

        return $cronjobMap;
    }

    /**
     * @param array $moduleCronjobsByModuleId
     *
     * @return array The input array
     */
    protected function flattenCronjobsMap(array $moduleCronjobsByModuleId)
    {
        $moduleCronjobsFlat = [];
        foreach ($moduleCronjobsByModuleId as $moduleCronjobsOfOneModule) {
            $moduleCronjobsFlat = array_merge($moduleCronjobsFlat, $moduleCronjobsOfOneModule);
        }
        return $moduleCronjobsFlat;
    }
}

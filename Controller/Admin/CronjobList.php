<?php

namespace Sioweb\Oxid\Cronjob\Controller\Admin;

use stdClass;
use OxidEsales\Eshop\Core\Registry;
use Ci\Oxid\Hotspots\Legacy\Model\Hotspot;
use OxidEsales\Eshop\Core\UtilsDate;
use OxidEsales\Eshop\Core\TableViewNameGenerator;
use OxidEsales\Eshop\Application\Controller\Admin\AdminListController;

class CronjobList extends AdminListController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'sio_cronjob_admin_list.tpl';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = \Sioweb\Oxid\Cronjob\Model\CronjobList::class;

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSortField = 'oxtitle';

    public function render()
    {
        parent::render();

        // passing display type back to view
        $this->_aViewData["displaytype"] = Registry::getConfig()->getRequestParameter("displaytype");
        return $this->_sThisTemplate;
    }

    protected function _prepareWhereQuery($aWhere, $sqlFull)
    {
        $sQ = parent::_prepareWhereQuery($aWhere, $sqlFull);
        $sDisplayType = (int) Registry::getConfig()->getRequestParameter('displaytype');
        
        $ViewNameGenerator = Registry::get(TableViewNameGenerator::class);
        $sTable = $ViewNameGenerator->getViewName(Hotspot::class);

        //searchong for empty oxfolder fields
        if ($sDisplayType) {

            $sNow = date('Y-m-d H:i:s', Registry::get(UtilsDate::class)->getTime());

            switch ($sDisplayType) {
                case 1: // active
                    $sQ .= " and {$sTable}.oxactivefrom < '{$sNow}' and {$sTable}.oxactiveto > '{$sNow}' ";
                    break;
                case 2: // upcoming
                    $sQ .= " and {$sTable}.oxactivefrom > '{$sNow}' ";
                    break;
                case 3: // expired
                    $sQ .= " and {$sTable}.oxactiveto < '{$sNow}' and {$sTable}.oxactiveto != '0000-00-00 00:00:00' ";
                    break;
            }
        }
        return $sQ;
    }
}
<?php

namespace Sioweb\Oxid\Cronjob\Controller\Admin;

use stdClass;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\Registry;
use Ci\Oxid\FormBuilder\Core\FormRender;
use Sioweb\Lib\Formgenerator\Core\Form as FormGenerator;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use Sioweb\Oxid\Cronjob\Model\Cronjob as CronjobModel;

class CronjobMain extends AdminDetailsController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'sio_cronjob_admin_main.tpl';

    /** New Shop indicator. */
    const NEW_SHOP_ID = '-1';

    public function render()
    {
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        
        $Form = new FormGenerator(
            new FormRender,
            oxNew(\Sioweb\Oxid\Cronjob\Form\Admin\Cronjob::class)
        );

        if (isset($soxId) && $soxId != self::NEW_SHOP_ID) {
            // load object
            $Cronjob = oxNew(CronjobModel::class);
            $Cronjob->loadInLang($this->_iEditLang, $soxId);
            

            
            $oOtherLang = $Cronjob->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                $Cronjob->loadInLang(key($oOtherLang), $soxId);
            }

            $FormData = ['editval' => []];
            foreach ($Cronjob->getFieldNames() as $name) {
                if ($Cronjob->{'sio_cronjob__' . $name}->value !== null) {
                    $FormData['editval']['sio_cronjob__' . $name] = $Cronjob->{'sio_cronjob__' . $name}->value;
                }
            }
            
            $FormData['subjlang'] = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("subjlang");
            $Form->setFieldValues($FormData);
        }
        
        $Form->ignorePostData([
            'subjlang'
        ]);
        $Form->setFormData();

        $this->_aViewData["form"] = implode("\n", $Form->generate());
        return $this->_sThisTemplate;
    }

    public function save()
    {
        parent::save();

        $config = $this->getConfig();

        $Cronjob = oxNew(CronjobModel::class);

        if ($this->isNewEditObject() !== true) {
            $Cronjob->load($this->getEditObjectId());
        }

        if ($this->checkAccessToEditCronjob($Cronjob) === true) {
            $Cronjob->assign($this->getCronjobFormData());
            $Cronjob->setLanguage($this->_iEditLang);
            // $Cronjob = Registry::getUtilsFile()->processFiles($Cronjob);
            
            $Cronjob->save();

            $this->setEditObjectId($Cronjob->getId());
        }
    }

    /**
     * Saves changed selected hotspot parameters in different language.
     */
    public function saveinnlang()
    {
        $this->save();
    }

    /**
     * Checks access to edit Cronjob.
     *
     * @param CronjobModel $Cronjob
     *
     * @return bool
     */
    protected function checkAccessToEditCronjob(CronjobModel $Cronjob)
    {
        return true;
    }

    /**
     * Returns form data for Cronjob.
     *
     * @return array
     */
    private function getCronjobFormData()
    {
        $request    = oxNew(Request::class);
        $formData   = $request->getRequestEscapedParameter("editval");
        $formData   = $this->normalizeCronjobFormData($formData);
    
        return $formData;
    }

    /**
     * Normalizes form data for Cronjob.
     *
     * @param   array $formData
     *
     * @return  array
     */
    private function normalizeCronjobFormData($formData)
    {
        if ($this->isNewEditObject() === true) {
            $formData['sio_cronjob__oxid'] = null;
        }

        if (!$formData['sio_cronjob__oxactive']) {
            $formData['sio_cronjob__oxactive'] = 0;
        }

        return $formData;
    }
}

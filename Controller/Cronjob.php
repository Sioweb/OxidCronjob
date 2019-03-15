<?php

namespace Sioweb\Oxid\Cronjob\Controller;

use ReflectionMethod;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\TableViewNameGenerator;
use Sioweb\Oxid\Cronjob\Core\Routing\CronjobClassNameResolver;
use OxidEsales\Eshop\Core\Output;
use Sioweb\Oxid\Cronjob\Core\Routing\Module\CronjobClassProviderStorage;

class Cronjob extends FrontendController
{
    private $output = [];


    /**
     * output handler object
     *
     * @see _getOuput
     *
     * @var oxOutput
     */
    protected $_oOutput = null;

    public function init()
    {
        $this->output = [];
        $ProviderStorage = new CronjobClassProviderStorage;
        $CronjobClasses = $ProviderStorage->get();
        foreach ($CronjobClasses as $ModuleId => $Classes) {
            foreach ($Classes as $CronjobKey => $CronjobClass) {
                $view = $this->_initializeCronjobObject($CronjobKey, null);
                $this->outputCron($CronjobKey, $CronjobClass, $view);
            }
        }

        $this->cronFinished($view);
    }

    protected function cronFinished($view)
    {
        $config = $this->getConfig();

        $outputManager = $this->_getCronOutputManager();
        $outputManager->setCharset($view->getCharSet());

        if ($config->getRequestParameter('renderPartial')) {
            $outputManager->setOutputFormat(Output::OUTPUT_FORMAT_JSON);
            $outputManager->output('errors', $this->_getFormattedErrors($view->getClassName()));
        }
        $outputManager->sendHeaders();
        $this->sendAdditionalHeaders($view);
        $outputManager->output('content', $this->finalizeOutput($this->output));
        $config->pageClose();
        $outputManager->flushOutput();
    }

    protected function finalizeOutput($output)
    {
        return '<div>' . implode("</div>\n<div>", $output) . '</div>';
    }

    /**
     * Returns class id of controller which should be loaded.
     * When in doubt returns default start controller class.
     *
     * @param string $cronjobKey Controller id
     *
     * @throws RoutingException
     * @return string
     */
    protected function resolveCronjobClass($cronjobKey)
    {
        $resolvedClass = Registry::get(CronjobClassNameResolver::class)->getClassNameById($cronjobKey);

        // If unmatched controller id is requested throw exception
        if (!$resolvedClass) {
            throw new \OxidEsales\Eshop\Core\Exception\RoutingException($cronjobKey);
        }

        return $resolvedClass;
    }

    /**
     * Initialize and return view object.
     *
     * @param string $class      View class
     * @param string $function   Function name
     * @param array  $parameters Parameters array
     * @param array  $viewsChain Array of views names that should be initialized also
     *
     * @return FrontendController
     */
    protected function _initializeCronjobObject($classKey, $function = null, $parameters = null, $viewsChain = null)
    {
        $NamespacedClass = $this->resolveCronjobClass($classKey);

        /** @var FrontendController $view */
        $view = oxNew($NamespacedClass);

        $view->setClassKey($classKey);
        $view->setFncName($function);
        $view->setViewParameters($parameters);

        $this->getConfig()->setActiveView($view);

        $this->onCronViewCreation($view);

        $view->init();

        return $view;
    }

    protected function outputCron($CronjobKey, $CronClass, $view)
    {
        $this->executeCronAction($view, $view->getFncName());
        $this->output[] = $CronjobKey.': '.$this->formOutput($view);
    }

    /**
     * Event for any actions during view creation.
     *
     * @param FrontendController $view
     */
    protected function onCronViewCreation($view)
    {
    }

    /**
     * Executes provided function on view object.
     * If this function can not be executed (is protected or so), oxSystemComponentException exception is thrown.
     *
     * @param FrontendController $view
     * @param string             $functionName
     *
     * @throws oxSystemComponentException
     */
    protected function executeCronAction($view, $functionName)
    {
        if (!$this->_canExecuteCronFunction($view, $functionName)) {
            throw oxNew('oxSystemComponentException', 'Non public method cannot be accessed');
        }

        $view->executeFunction($functionName);
    }

    /**
     * Check if method can be executed.
     *
     * @param FrontendController $view     View object to check if its method can be executed.
     * @param string             $function Method to check if it can be executed.
     *
     * @return bool
     */
    protected function _canExecuteCronFunction($view, $function)
    {
        $canExecute = true;
        if (method_exists($view, $function)) {
            $reflectionMethod = new ReflectionMethod($view, $function);
            if (!$reflectionMethod->isPublic()) {
                $canExecute = false;
            }
        }

        return $canExecute;
    }

    /**
     * Forms output from view object.
     *
     * @param FrontendController $view
     *
     * @return string
     */
    protected function formOutput($view)
    {
        return $this->_render($view);
    }

    /**
     * Render BaseController object.
     *
     * @param FrontendController $view view object to render
     *
     * @return string
     */
    protected function _render($view)
    {
        // get Smarty is important here as it sets template directory correct
        $smarty = Registry::getUtilsView()->getSmarty();

        // render it
        $templateName = $view->render();

        // check if template dir exists
        $templateFile = $this->getConfig()->getTemplatePath($templateName, $this->isAdmin());
        if (!file_exists($templateFile)) {
            $ex = oxNew(\OxidEsales\Eshop\Core\Exception\SystemComponentException::class);
            $ex->setMessage('EXCEPTION_SYSTEMCOMPONENT_TEMPLATENOTFOUND' . ' ' . $templateName);
            $ex->setComponent($templateName);

            $templateName = "message/exception.tpl";

            if ($this->_isDebugMode()) {
                Registry::getUtilsView()->addErrorToDisplay($ex);
            }
            $ex->debugOut();
        }

        // Output processing. This is useful for modules. As sometimes you may want to process output manually.
        $outputManager = $this->_getCronOutputManager();
        $viewData = $outputManager->processViewArray($view->getViewData(), $view->getClassName());
        $view->setViewData($viewData);

        //add all exceptions to display
        $errors = $this->_getErrors($view->getClassName());
        if (is_array($errors) && count($errors)) {
            Registry::getUtilsView()->passAllErrorsToView($viewData, $errors);
        }

        foreach (array_keys($viewData) as $viewName) {
            $smarty->assign_by_ref($viewName, $viewData[$viewName]);
        }

        // passing current view object to smarty
        $smarty->oxobject = $view;

        $output = $smarty->fetch($templateName, $view->getViewId());

        //Output processing - useful for modules as sometimes you may want to process output manually.
        $output = $outputManager->process($output, $view->getClassName());

        return $outputManager->addVersionTags($output);
    }

    /**
     * Return output handler.
     *
     * @return oxOutput
     */
    protected function _getCronOutputManager()
    {
        if (!$this->_oOutput) {
            $this->_oOutput = oxNew(Output::class);
        }

        return $this->_oOutput;
    }

    /**
     * Return page errors array.
     *
     * @param string $currentControllerName Class name
     *
     * @return array
     */
    protected function _getErrors($currentControllerName)
    {
        if (null === $this->_aErrors) {
            $this->_aErrors = Registry::getSession()->getVariable('Errors');
            $this->_aControllerErrors = Registry::getSession()->getVariable('ErrorController');
            if (null === $this->_aErrors) {
                $this->_aErrors = [];
            }
            $this->_aAllErrors = $this->_aErrors;
        }
        // resetting errors of current controller or widget from session
        if (is_array($this->_aControllerErrors) && !empty($this->_aControllerErrors)) {
            foreach ($this->_aControllerErrors as $errorName => $controllerName) {
                if ($controllerName == $currentControllerName) {
                    unset($this->_aAllErrors[$errorName]);
                    unset($this->_aControllerErrors[$errorName]);
                }
            }
        } else {
            $this->_aAllErrors = [];
        }
        Registry::getSession()->setVariable('ErrorController', $this->_aControllerErrors);
        Registry::getSession()->setVariable('Errors', $this->_aAllErrors);

        return $this->_aErrors;
    }
}
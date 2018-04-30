<?php
namespace OpenInvoices\Mvc\Controller;

use Zend\Mvc\Controller\AbstractActionController as BaseAbstractActionController;

abstract class AbstractActionController extends BaseAbstractActionController
{
    /**
     * Action called if matched action does not exist
     *
     * @return ViewModel
     */
    public function forbiddenAction()
    {
        $event      = $this->getEvent();
        $routeMatch = $event->getRouteMatch();
        $routeMatch->setParam('action', 'forbidden');
        
        $helper = $this->plugin('createHttpForbiddenModel');
        return $helper($event->getResponse());
    }
}
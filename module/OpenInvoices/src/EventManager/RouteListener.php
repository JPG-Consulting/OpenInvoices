<?php
namespace OpenInvoices\EventManager;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class RouteListener extends AbstractListenerAggregate
{
    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     * @param int                   $priority
     * @return void
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onPreRoute'), -100);
    }
    
    public function onPreRoute(MvcEvent $e)
    {
        $app = $e->getApplication();
        $routeMatch = $e->getRouteMatch();
        $sm = $app->getServiceManager();
        
        $auth = $sm->get(\Zend\Authentication\AuthenticationService::class);
        
        if (!$auth->hasIdentity() && $routeMatch->getMatchedRouteName() != 'login') {
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine(
                'Location',
                $e->getRouter()->assemble(
                    array(),
                    array('name' => 'login')
                    )
                );
            $response->setStatusCode(302);
            return $response;
        }
        
        // RBAC
        if ($routeMatch->getMatchedRouteName() != 'login')
        {
            $rbac = $sm->get(\Zend\Permissions\Rbac\Rbac::class);
            
            var_dump($rbac);die();
        }
    }
    
}
<?php
namespace OpenInvoices\Service;

use Interop\Container\ContainerInterface;
use OpenInvoices\EventManager\RouteListener;
use Zend\ServiceManager\Factory\FactoryInterface;

class RouteListenerFactory implements FactoryInterface
{
    /**
     * Create the default dispatch listener.
     *
     * @param  ContainerInterface $container
     * @param  string $name
     * @param  null|array $options
     * @return DispatchListener
     */
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $eventManager = $container->get(\Zend\EventManager\EventManagerInterface::class);
        
        return new RouteListener($eventManager);
    }
}
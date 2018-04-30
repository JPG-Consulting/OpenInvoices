<?php
namespace OpenInvoices\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use OpenInvoices\Authentication\Adapter\CredentialAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthenticationServiceFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     *
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        
        $authAdapter = new CredentialAdapter($dbAdapter, 'users', 'username', 'password');
        
        $sessionManager = $container->get(\Zend\Session\SessionManager::class);
        $sessionStorage = new SessionStorage('token', null, $sessionManager);
        
        return new AuthenticationService($sessionStorage, $authAdapter);
    }
}
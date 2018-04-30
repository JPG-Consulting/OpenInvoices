<?php
namespace OpenInvoices\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql;
use Zend\Permissions\Rbac\Rbac;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class RbacFactory implements FactoryInterface
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
        
        $rbac = new Rbac();
        
        $roles = $this->getRootRoles($dbAdapter, 'roles');
        
        foreach ($roles as $role)
        {
            $rbac->addRole($role);
        }
       
        return $rbac;
    }
    
    private function getRootRoles(AdapterInterface $zendDb, $rolesTableName)
    {
        $roles = [];
        
        $sql = new Sql\Sql($zendDb, $rolesTableName);
        $select = $sql->select();
        $select->where(array('parent_id' => null));
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);
            
            foreach ($resultSet as $row) {
                $roles[] = $row->name;
            }
        }
        
        return $roles;
    }
}
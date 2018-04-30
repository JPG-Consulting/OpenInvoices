<?php
namespace OpenInvoices\Authentication\Adapter;

use Zend\Authentication\Adapter\DbTable\AbstractAdapter;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\Db\Sql\Predicate\Operator as SqlOp;

class CredentialAdapter extends AbstractAdapter
{
    /**
     * _authenticateCreateSelect() - This method creates a Zend\Db\Sql\Select object that
     * is completely configured to be queried against the database.
     *
     * @return Sql\Select
     */
    protected function authenticateCreateSelect()
    {   
        // get select
        $dbSelect = clone $this->getDbSelect();
        $dbSelect->from($this->tableName)
                 ->columns(['*'])
                 ->where(new SqlOp($this->identityColumn, '=', $this->identity));
        
        return $dbSelect;
    }
    
    /**
     * _authenticateValidateResult() - This method attempts to validate that
     * the record in the resultset is indeed a record that matched the
     * identity provided to this adapter.
     *
     * @param  array $resultIdentity
     * @return AuthenticationResult
     */
    protected function authenticateValidateResult($resultIdentity)
    {
        if (!$resultIdentity['active'])
        {
            $this->authenticateResultInfo['code']       = AuthenticationResult::FAILURE_UNCATEGORIZED;
            $this->authenticateResultInfo['messages'][] = 'The account is inactive.';
            return $this->authenticateCreateAuthResult();
        }
        elseif (!password_verify($this->credential, $resultIdentity[$this->credentialColumn]))
        {
            $this->authenticateResultInfo['code']       = AuthenticationResult::FAILURE_CREDENTIAL_INVALID;
            $this->authenticateResultInfo['messages'][] = 'Supplied credential is invalid.';
            return $this->authenticateCreateAuthResult();
        }
        
        unset($resultIdentity['zend_auth_credential_match']);
        $this->resultRow = $resultIdentity;
        
        $this->authenticateResultInfo['code']       = AuthenticationResult::SUCCESS;
        $this->authenticateResultInfo['messages'][] = 'Authentication successful.';
        return $this->authenticateCreateAuthResult();
    }
}
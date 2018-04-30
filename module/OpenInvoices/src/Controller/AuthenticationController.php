<?php
namespace OpenInvoices\Controller;

use OpenInvoices\Form\LoginForm;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthenticationController extends AbstractActionController
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;
    
    /**
     * AuthenticationController constructor.
     *
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }
    
    public function loginAction()
    {
        $this->layout()->setTemplate('layout/unauthenticated');
        
        // Create login form
        $form = new LoginForm();
        
        // Store login status.
        $isLoginError = false;
        
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) 
            {
                // Get filtered and validated data
                $data = $form->getData();
                
                $adapter = $this->authenticationService->getAdapter();
                $adapter->setIdentity($data['email']);
                $adapter->setCredential($data['password']);
                
                $result = $this->authenticationService->authenticate();
                if ($result->isValid())
                {
                    $this->redirect()->toRoute('home');
                }
                else 
                {
                    $isLoginError = false;
                }
            }
            else
            {
                $isLoginError = false;
            }
        }
        
        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
        ]);
    }
    
    public function logoutAction()
    {
        $this->authenticationService->clearIdentity();
        $this->redirect()->toRoute('login');
    }
}

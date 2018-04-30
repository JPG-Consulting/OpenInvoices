<?php
namespace OpenInvoices\Mvc\Controller\Plugin;

use Zend\Http\Response;
use Zend\View\Model\ViewModel;

class CreateHttpNotFoundModel extends AbstractPlugin
{
    /**
     * Create an HTTP view model representing a "forbidden" page
     *
     * @param  Response $response
     *
     * @return ViewModel
     */
    public function __invoke(Response $response)
    {
        $response->setStatusCode(403);
        
        return new ViewModel(['content' => 'Forbidden']);
    }
}

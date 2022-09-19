<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Customer;
use App\Form\Type\CustomerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;

class CustomerController extends AbstractApiController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    public function indexAction(Request $request): Response
    {
        $customers = $this->doctrine->getRepository(Customer::class)->findAll();

        return $this->json($customers);
    }

    public function createAction(ManagerRegistry $doctrine,Request $request)
    {
        $form = $this->buildForm(CustomerType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            // throw exception
            print 'Error';
            exit();
        }

        /** @var Customer $customer*/
        $customer = $form->getData();

        $this->doctrine->getManager()->persist($customer);
        $this->doctrine->getManager()->flush();

        return $this->json('');
    }
}
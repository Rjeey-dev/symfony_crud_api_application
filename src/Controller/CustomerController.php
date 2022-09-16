<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;

class CustomerController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine) {

    }

    public function indexAction(Request $request): Response
    {
        $customers = $this->doctrine->getRepository(Customer::class)->findAll();

        return $this->json($customers);
    }

    public function createAction(Request $request)
    {
        return $this->json('');
    }
}
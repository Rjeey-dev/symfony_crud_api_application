<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Form\Type\CartType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends AbstractApiController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    public function showAction(Request $request): Response
    {
        $customerId = $request->get('id');
        $customer = $this->doctrine->getRepository(Cart::class)->findOneBy(['id' => $customerId]);

        if (!$customerId) {
            throw new NotFoundHttpException('Customer not found');
        }

        $cart = $this->doctrine->getRepository(Cart::class)->findOneBy([
            'customer' => $customer,
        ]);

        if (!$cart) {
            throw new NotFoundHttpException('Cart does not exist for this customer');
        }

        return $this->respond($cart);
    }

    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(CartType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Cart $cart*/
        $cart = $form->getData();
        $this->doctrine->getManager()->persist($cart);
        $this->doctrine->getManager()->flush();
        return $this->respond($cart);
    }

    public function updateAction(Request $request): Response
    {
        $customerId = $request->get('customerId');

        $customer = $this->getDoctrine()->getRepository(Customer::class)->findOneBy(['id' => $customerId]);

        if (!$customer) {
            throw new NotFoundHttpException('Customer not found');
        }

        $cart = $this->getDoctrine()->getRepository(Cart::class)->findOneBy([
            'customer' => $customer,
        ]);

        $form = $this->buildForm(CartType::class, $cart, [
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Cart $cart */
        $cart = $form->getData();

        $this->doctrine->getManager()->persist($cart);
        $this->doctrine->getManager()->flush();

        return $this->respond($cart);
    }

    public function deleteAction(Request $request): Response
    {
        $cartId = $request->get('cartId');
        $customerId = $request->get('customerId');

        $cart = $this->doctrine->getRepository(Cart::class)->findOneBy([
            'customer' => $customerId,
        ]);

        if (!$cart) {
            throw new NotFoundHttpException('Cart not found');
        }

        $this->doctrine->getManager()->remove($cart);
        $this->doctrine->getManager()->flush($cart);

        return $this->respond(null);
    }
}
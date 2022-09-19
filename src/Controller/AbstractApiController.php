<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

abstract class AbstractApiController extends AbstractController
{
    protected function buildForm(string $type, $data = null,array $options = []) : FormInterface
    {
        $options = array_merge($options, [
            'csrf_protection' => false,
        ]);

        return $this->container->get('form_factory')->createNamed('',$type,$data,$options);
    }
}
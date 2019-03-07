<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route(
     *     "/dashboard",
     *     name="admin",
     *     host="admin.{domain}",
     *     defaults={"domain"="%domain%"},
     *     requirements={"domain"="%domain%"}
     * )
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}

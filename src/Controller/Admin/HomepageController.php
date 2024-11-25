<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Config\Role;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted(Role::ADMIN->value)]
class HomepageController extends AbstractController
{
    #[Route('/', name: 'admin_homepage_index')]
    public function index(): Response
    {
        return $this->render('admin/homepage/index.html.twig', [
            'controller_name' => 'Admin/HomepageController',
        ]);
    }
}

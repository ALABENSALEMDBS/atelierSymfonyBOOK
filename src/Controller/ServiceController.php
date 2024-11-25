<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{

    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }



    #[Route('/service/goto-index', name: 'service_go_to_index')]
    public function goToIndex(): Response
    {
        // Rediriger vers la route définie dans HomeController
        return $this->redirectToRoute('app_home');
    }


    
    #[Route('/service/{name}', name:'service_show')]
    public function showService(string $name): Response
    {
        return $this->render('service/showService.html.twig', [
            'name' => $name,
        ]);
    }


    


}

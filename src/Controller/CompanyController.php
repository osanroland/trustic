<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompanyController extends AbstractController
{
    #[Route('/companies', name: 'company_index')]
    public function statistics(ReviewRepository $repository): Response
    {
        return $this->render('company/statistics.html.twig', [
            'companies' => $repository->getCompanyStatistics(),
        ]);
    }
}

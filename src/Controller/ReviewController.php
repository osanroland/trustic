<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReviewController extends AbstractController
{
    #[Route('/', name: 'review_index')]
    public function index(Request $request, ReviewRepository $repository): Response
    {
        $search = $request->query->getString('search') ?: null;
        $reviews = $repository->findAllOrderedByDate($search);

        return $this->render('review/index.html.twig', [
            'reviews' => $reviews,
            'search' => $search ?? '',
        ]);
    }

    #[Route('/review/add', name: 'review_add', methods: ['GET', 'POST'])]
    public function addReview(Request $request, EntityManagerInterface $em): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($review);
            $em->flush();

            $this->addFlash('success', 'Köszönjük a véleményed!');

            return $this->redirectToRoute('review_index');
        }

        return $this->render('review/reviewForm.html.twig', [
            'form' => $form,
        ]);
    }
}

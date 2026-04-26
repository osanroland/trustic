<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReviewController extends AbstractController
{
    private const REVIEWS_PER_PAGE = 10;

    public function __construct(private readonly ReviewRepository $repository) {}

    #[Route('/', name: 'review_index')]
    public function index(Request $request): Response
    {
        $search = $request->query->getString('search') ?: null;
        $page = max(1, $request->query->getInt('page', 1));

        $paginator = $this->repository->findPaginatedList($page, self::REVIEWS_PER_PAGE, $search);
        $totalPages = (int) ceil(count($paginator) / self::REVIEWS_PER_PAGE);

        return $this->render('review/index.html.twig', [
            'reviews' => $paginator,
            'search' => $search ?? '',
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/review/{id}/details', name: 'review_details', methods: ['GET'])]
    public function details(int $id): Response
    {
        $review = $this->repository->find($id) ?? throw $this->createNotFoundException('A vélemény nem található.');

        return $this->render('review/details.html.twig', [
            'review' => $review,
        ]);
    }

    #[Route('/review/add', name: 'review_add', methods: ['GET', 'POST'])]
    public function addReview(Request $request): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->save($review);

            $this->addFlash('success', 'Köszönjük a véleményed!');

            return $this->redirectToRoute('review_index');
        }

        return $this->render('review/reviewForm.html.twig', [
            'form' => $form,
        ]);
    }
}

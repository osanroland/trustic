<?php

namespace App\Tests\Functional;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CompanyControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $this->ensureSchemaExists();
        $this->clearReviews();
    }

    public function testCompaniesAreOrderedByAverageRatingDescending(): void
    {
        $this->insertReview('Apple', 2);
        $this->insertReview('Apple', 4);

        $this->insertReview('Google', 5);
        $this->insertReview('Google', 5);

        $this->insertReview('Tesla', 1);

        $crawler = $this->client->request('GET', '/companies');

        $this->assertResponseIsSuccessful();

        $companyNames = $crawler->filter('table tbody tr td:first-child')->each(
            fn ($node) => $node->text()
        );

        $this->assertSame(['Google', 'Apple', 'Tesla'], $companyNames);
    }

    public function testCompanyRowShowsCorrectReviewCount(): void
    {
        $this->insertReview('Microsoft', 3);
        $this->insertReview('Microsoft', 5);
        $this->insertReview('Microsoft', 4);

        $crawler = $this->client->request('GET', '/companies');

        $this->assertResponseIsSuccessful();

        $count = $crawler->filter('table tbody tr td:nth-child(2)')->first()->text();
        $this->assertSame('3', $count);
    }

    private function insertReview(string $companyName, int $rating): void
    {
        $review = new Review();
        $review->setCompanyName($companyName);
        $review->setRating($rating);
        $review->setReviewText('Tesztvélemény');
        $review->setAuthorEmail(uniqid('test').'@example.com');

        $this->em->persist($review);
        $this->em->flush();
    }

    private function clearReviews(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\Review')->execute();
    }

    private function ensureSchemaExists(): void
    {
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->updateSchema($this->em->getMetadataFactory()->getAllMetadata(), true);
    }
}

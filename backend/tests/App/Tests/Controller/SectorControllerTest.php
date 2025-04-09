<?php

namespace App\Tests\Controller;

use App\Entity\Sector;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SectorControllerTest extends WebTestCase
{

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->container = static::getContainer();
        $this->em = $this->container->get(EntityManagerInterface::class);

        $this->em->createQuery('DELETE FROM App\Entity\UserSubmissionSector')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\UserSubmission')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Sector')->execute();
    }

    private function createSector(): Sector
    {
        $sector = new Sector();
        $sector->setLabel('Test Sector');
        $this->em->persist($sector);
        $this->em->flush();

        return $sector;
    }

    public function testGetSectors(): void
    {
        $sector = $this->createSector();

        $this->client->request('GET', '/api/sectors');
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent(), true);

        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('label', $data[0]);
        $this->assertEquals($sector->getLabel(), $data[0]['label']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSaveSubmissionAndFetchMe(): void
    {
        $this->createSector();

        $this->client->request('GET', '/api/sectors');
        $sectors = json_decode($this->client->getResponse()->getContent(), true);
        $sectorId = $sectors[0]['id'];

        $postData = [
            'name' => 'John Doe',
            'agreed' => true,
            'sectors' => [$sectorId]
        ];

        $this->client->request(
            'POST',
            '/api/save',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($postData)
        );

        $this->assertResponseIsSuccessful();
        $this->assertEquals('Data saved successfully!', json_decode($this->client->getResponse()->getContent(), true)['message']);

        $this->client->request('GET', '/api/me');
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $userData = json_decode($response->getContent(), true);

        $this->assertEquals('John Doe', $userData['name']);
        $this->assertTrue($userData['agreed']);
        $this->assertEquals($sectorId, $userData['sectors'][0]['id']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSaveSubmissionWithInvalidData(): void
    {
        $this->client->request(
            'POST',
            '/api/save',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => '',
                'agreed' => true,
                'sectors' => []
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertStringContainsString('All fields are required', $this->client->getResponse()->getContent());
    }

    /**
     * @runInSeparateProcess
     */
    public function testSaveSubmissionWithMissingFields(): void
    {

        $this->client->request(
            'POST',
            '/api/save',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'agreed' => true,
                'sectors' => [1]
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertStringContainsString('All fields are required.', $this->client->getResponse()->getContent());

        $this->client->request(
            'POST',
            '/api/save',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Martin',
                'agreed' => true
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertStringContainsString('All fields are required.', $this->client->getResponse()->getContent());
    }

    /**
     * @runInSeparateProcess
     */
    public function testSaveSubmissionWithInvalidSectorId(): void
    {
        $this->client->request(
            'POST',
            '/api/save',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Martin',
                'agreed' => true,
                'sectors' => [999]
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertStringContainsString('Sector with ID', $this->client->getResponse()->getContent());
    }
}

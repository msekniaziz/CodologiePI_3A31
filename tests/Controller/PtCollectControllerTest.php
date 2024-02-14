<?php

namespace App\Test\Controller;

use App\Entity\PtCollect;
use App\Repository\PtCollectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PtCollectControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private PtCollectRepository $repository;
    private string $path = '/ptc/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(PtCollect::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('PtCollect index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'pt_collect[TypeM]' => 'Testing',
            'pt_collect[adresse]' => 'Testing',
            'pt_collect[emploi]' => 'Testing',
            'pt_collect[nomptc]' => 'Testing',
        ]);

        self::assertResponseRedirects('/ptc/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new PtCollect();
        $fixture->setTypeM('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setEmploi('My Title');
        $fixture->setNomptc('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('PtCollect');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new PtCollect();
        $fixture->setTypeM('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setEmploi('My Title');
        $fixture->setNomptc('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'pt_collect[TypeM]' => 'Something New',
            'pt_collect[adresse]' => 'Something New',
            'pt_collect[emploi]' => 'Something New',
            'pt_collect[nomptc]' => 'Something New',
        ]);

        self::assertResponseRedirects('/ptc/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTypeM());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getEmploi());
        self::assertSame('Something New', $fixture[0]->getNomptc());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new PtCollect();
        $fixture->setTypeM('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setEmploi('My Title');
        $fixture->setNomptc('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/ptc/');
    }
}

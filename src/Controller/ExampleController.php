<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TypeDispoRepository;
use App\Repository\PtCollectRepository;


#[Route('/example')]
class ExampleController extends AbstractController
{
    #[Route('/', name: 'example_index')]
    public function index(TypeDispoRepository $typeDispoRepository, PtCollectRepository $ptCollectRepository): Response
    {
        // Récupérer les entités TypeDispo et PtCollect depuis la base de données
        $typeDispos = $typeDispoRepository->findAll();
        $ptCollects = $ptCollectRepository->findAll();

        // Passer les données à la vue Twig
        return $this->render('pt_collect/mtm.html.twig', [
            'typeDispos' => $typeDispos,
            'ptCollects' => $ptCollects,
        ]);
    }
}

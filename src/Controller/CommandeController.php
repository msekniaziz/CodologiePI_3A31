<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Commandes;
use App\Entity\Annonces;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;



class CommandeController extends AbstractController
{
    #[Route('/commande/{id}', name: 'app_commande')]
    public function index($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $annonce = $entityManager->getRepository(Annonces::class)->find($id);

        return $this->render('commande/index.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    #[Route('/commande/new/{id}', name: 'newcommande')]
    public function add(Request $request, $id, FlashBagInterface $flashBag): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $annonce = $entityManager->getRepository(Annonces::class)->find($id);

        if (!$annonce) {
            throw $this->createNotFoundException('Annonce non trouvée');
        }

        $commande = new Commandes();

        $commande->setEtat('1');
        $dateNow = new \DateTime();
        $commande->setDate($dateNow);
        $annonce->setStatus('1');

        $entityManager->persist($commande);
        $entityManager->flush();

        return $this->redirectToRoute('aff_annonces', ['commande_success' => true]);


    }
}

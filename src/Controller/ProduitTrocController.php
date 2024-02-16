<?php

namespace App\Controller;

use App\Entity\ProduitTroc;
use App\Form\ProduitTroc1Type;
use App\Repository\ProduitTrocRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produit/troc')]
class ProduitTrocController extends AbstractController
{
    #[Route('/', name: 'app_produit_troc_index', methods: ['GET'])]
    public function index(ProduitTrocRepository $produitTrocRepository): Response
    {
        return $this->render('produit_troc/index.html.twig', [
            'produit_trocs' => $produitTrocRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_troc_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {$user=$this->getUser();
        $produitTroc = new ProduitTroc();
        $form = $this->createForm(ProduitTroc1Type::class, $produitTroc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitTroc->setIdUser($user);
            $entityManager->persist($produitTroc);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_troc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit_troc/new.html.twig', [
            'produit_troc' => $produitTroc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_troc_show', methods: ['GET'])]
    public function show(ProduitTroc $produitTroc): Response
    {
        return $this->render('produit_troc/show.html.twig', [
            'produit_troc' => $produitTroc,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_troc_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProduitTroc $produitTroc, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitTroc1Type::class, $produitTroc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_troc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit_troc/edit.html.twig', [
            'produit_troc' => $produitTroc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_troc_delete', methods: ['POST'])]
    public function delete(Request $request, ProduitTroc $produitTroc, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produitTroc->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produitTroc);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_troc_index', [], Response::HTTP_SEE_OTHER);
    }
}

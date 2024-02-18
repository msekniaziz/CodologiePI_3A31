<?php

namespace App\Controller;

use App\Entity\ProduitTrocWith;
use App\Form\ProduitTrocWithType;
use App\Repository\ProduitTrocWithRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produit/troc/with')]
class ProduitTrocWithController extends AbstractController
{
    #[Route('/', name: 'app_produit_troc_with_index', methods: ['GET'])]
    public function index(ProduitTrocWithRepository $produitTrocWithRepository): Response
    {
        return $this->render('produit_troc_with/index.html.twig', [
            'produit_troc_withs' => $produitTrocWithRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_troc_with_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produitTrocWith = new ProduitTrocWith();
        $form = $this->createForm(ProduitTrocWithType::class, $produitTrocWith);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produitTrocWith);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_troc_with_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit_troc_with/new.html.twig', [
            'produit_troc_with' => $produitTrocWith,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_troc_with_show', methods: ['GET'])]
    public function show(ProduitTrocWith $produitTrocWith): Response
    {
        return $this->render('produit_troc_with/show.html.twig', [
            'produit_troc_with' => $produitTrocWith,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_troc_with_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProduitTrocWith $produitTrocWith, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitTrocWithType::class, $produitTrocWith);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_troc_with_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit_troc_with/edit.html.twig', [
            'produit_troc_with' => $produitTrocWith,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_troc_with_delete', methods: ['POST'])]
    public function delete(Request $request, ProduitTrocWith $produitTrocWith, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produitTrocWith->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produitTrocWith);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_troc_with_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\ProdR;
use App\Form\ProdRType;
use App\Repository\ProdRRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/prod/r')]
class ProdRController extends AbstractController
{
    #[Route('/', name: 'app_prod_r_index', methods: ['GET'])]
    public function index(ProdRRepository $prodRRepository): Response
    {
        return $this->render('prod_r/index.html.twig', [
            'prod_rs' => $prodRRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_prod_r_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prodR = new ProdR();
        $form = $this->createForm(ProdRType::class, $prodR);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prodR);
            $entityManager->flush();

            return $this->redirectToRoute('app_prod_r_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prod_r/new.html.twig', [
            'prod_r' => $prodR,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prod_r_show', methods: ['GET'])]
    public function show(ProdR $prodR): Response
    {
        return $this->render('prod_r/show.html.twig', [
            'prod_r' => $prodR,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prod_r_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProdR $prodR, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProdRType::class, $prodR);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prod_r_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prod_r/edit.html.twig', [
            'prod_r' => $prodR,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prod_r_delete', methods: ['POST'])]
    public function delete(Request $request, ProdR $prodR, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prodR->getId(), $request->request->get('_token'))) {
            $entityManager->remove($prodR);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prod_r_index', [], Response::HTTP_SEE_OTHER);
    }
}

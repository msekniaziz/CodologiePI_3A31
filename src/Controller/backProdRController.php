<?php

namespace App\Controller;

use App\Entity\ProdR;
use App\Entity\User;
// use App\Form\ProdRType;
use App\Form\backProdRType;
use App\Repository\ProdRRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/prodb')]
class backProdRController extends AbstractController
{
    #[Route('/back', name: 'app_prod_r_back_index', methods: ['GET'])]
    public function index1(ProdRRepository $prodRRepository): Response
    {
        return $this->render('prod_r_back/index.html.twig', [
            'prod_rs' => $prodRRepository->findAll(),
        ]);
    }

    #[Route('/back/new', name: 'app_prod_r_back_new', methods: ['GET', 'POST'])]
    public function new1(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prodR = new ProdR();
        $form = $this->createForm(backProdRType::class, $prodR);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $prodR->setUserId($user);

            // Persist et flush l'entité
            $entityManager->persist($prodR);
            $entityManager->flush();

            return $this->redirectToRoute('app_prod_r_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prod_r_back/new.html.twig', [
            'prod_r' => $prodR,
            // 'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/back/{id}', name: 'app_prod_r_back_show', methods: ['GET'])]
    public function show1(ProdR $prodR): Response
    {
        return $this->render('prod_r_back/show.html.twig', [
            'prod_r' => $prodR,
        ]);
    }

    #[Route('/back/{id}/edit', name: 'app_prod_r_back_edit', methods: ['GET', 'POST'])]
    public function edit1(Request $request, ProdR $prodR, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(backProdRType::class, $prodR);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prod_r_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prod_r_back/edit.html.twig', [
            'prod_r' => $prodR,
            'form' => $form,
        ]);
    }

    #[Route('/back/{id}', name: 'app_prod_r_back_delete', methods: ['POST'])]
    public function delete1(Request $request, ProdR $prodR, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $prodR->getId(), $request->request->get('_token'))) {
            $entityManager->remove($prodR);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prod_r_back_index', [], Response::HTTP_SEE_OTHER);
    }
}

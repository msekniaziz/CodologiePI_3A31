<?php

namespace App\Controller;

use App\Entity\DonArgent;
use App\Form\DonArgentType;
use App\Repository\DonArgentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/don/argent')]
class DonArgentController extends AbstractController
{
    #[Route('/', name: 'app_don_argent_index', methods: ['GET'])]
    public function index(DonArgentRepository $donArgentRepository): Response
    {
        return $this->render('don_argent/index.html.twig', [
            'don_argents' => $donArgentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_don_argent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donArgent = new DonArgent();
        $form = $this->createForm(DonArgentType::class, $donArgent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donArgent);
            $entityManager->flush();

            return $this->redirectToRoute('app_don_argent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don_argent/new.html.twig', [
            'don_argent' => $donArgent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_argent_show', methods: ['GET'])]
    public function show(DonArgent $donArgent): Response
    {
        return $this->render('don_argent/show.html.twig', [
            'don_argent' => $donArgent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_don_argent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DonArgent $donArgent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonArgentType::class, $donArgent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_don_argent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don_argent/edit.html.twig', [
            'don_argent' => $donArgent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_argent_delete', methods: ['POST'])]
    public function delete(Request $request, DonArgent $donArgent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donArgent->getId(), $request->request->get('_token'))) {
            $entityManager->remove($donArgent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_don_argent_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\TypeDispo;
use App\Form\TypeDispoType;
use App\Repository\TypeDispoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type/dispo')]
class TypeDispoController extends AbstractController
{
    #[Route('/', name: 'app_type_dispo_index', methods: ['GET'])]
    public function index(TypeDispoRepository $typeDispoRepository): Response
    {
        return $this->render('type_dispo/index.html.twig', [
            'type_dispos' => $typeDispoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_dispo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeDispo = new TypeDispo();
        $form = $this->createForm(TypeDispoType::class, $typeDispo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeDispo);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_dispo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_dispo/new.html.twig', [
            'type_dispo' => $typeDispo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_dispo_show', methods: ['GET'])]
    public function show(TypeDispo $typeDispo): Response
    {
        return $this->render('type_dispo/show.html.twig', [
            'type_dispo' => $typeDispo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_dispo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeDispo $typeDispo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeDispoType::class, $typeDispo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_dispo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_dispo/edit.html.twig', [
            'type_dispo' => $typeDispo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_dispo_delete', methods: ['POST'])]
    public function delete(Request $request, TypeDispo $typeDispo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $typeDispo->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typeDispo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_dispo_index', [], Response::HTTP_SEE_OTHER);
    }
}

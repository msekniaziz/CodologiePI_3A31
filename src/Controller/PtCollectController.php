<?php

namespace App\Controller;

use App\Entity\PtCollect;
use App\Form\PtCollectType;
use App\Repository\PtCollectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pt/collect')]
class PtCollectController extends AbstractController
{
    #[Route('/', name: 'app_pt_collect_index', methods: ['GET'])]
    public function index(PtCollectRepository $ptCollectRepository): Response
    {
        return $this->render('pt_collect/index.html.twig', [
            'pt_collects' => $ptCollectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pt_collect_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ptCollect = new PtCollect();
        $form = $this->createForm(PtCollectType::class, $ptCollect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ptCollect);
            $entityManager->flush();

            return $this->redirectToRoute('app_pt_collect_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pt_collect/new.html.twig', [
            'pt_collect' => $ptCollect,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pt_collect_show', methods: ['GET'])]
    public function show(PtCollect $ptCollect): Response
    {
        return $this->render('pt_collect/show.html.twig', [
            'pt_collect' => $ptCollect,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pt_collect_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PtCollect $ptCollect, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PtCollectType::class, $ptCollect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pt_collect_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pt_collect/edit.html.twig', [
            'pt_collect' => $ptCollect,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pt_collect_delete', methods: ['POST'])]
    public function delete(Request $request, PtCollect $ptCollect, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ptCollect->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ptCollect);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pt_collect_index', [], Response::HTTP_SEE_OTHER);
    }
}

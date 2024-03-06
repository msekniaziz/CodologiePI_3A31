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

#[Route('/ptc')]
class backPtCollectController extends AbstractController
{
    #[Route('/back', name: 'app_pt_collect_back_index', methods: ['GET'])]
    public function index1(PtCollectRepository $ptCollectRepository): Response
    {
        return $this->render('pt_collect_back/index.html.twig', [
            'pt_collects' => $ptCollectRepository->findAll(),
        ]);
    }

    #[Route('/back/new', name: 'app_pt_collect_back_new', methods: ['GET', 'POST'])]
    public function new1(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ptCollect = new PtCollect();
        $form = $this->createForm(PtCollectType::class, $ptCollect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ptCollect);
            $entityManager->flush();

            return $this->redirectToRoute('app_pt_collect_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pt_collect_back/new.html.twig', [
            'pt_collect' => $ptCollect,
            'form' => $form,
        ]);
    }

    #[Route('/back/{id}', name: 'app_pt_collect_back_show', methods: ['GET'])]
    public function show1(PtCollect $ptCollect): Response
    {
        return $this->render('pt_collect_back/show.html.twig', [
            'pt_collect' => $ptCollect,
        ]);
    }

    #[Route('/back/{id}/edit', name: 'app_pt_collect_back_edit', methods: ['GET', 'POST'])]
    public function edit1(Request $request, PtCollect $ptCollect, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PtCollectType::class, $ptCollect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pt_collect_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pt_collect_back/edit.html.twig', [
            'pt_collect' => $ptCollect,
            'form' => $form,
        ]);
    }

    #[Route('/back/{id}', name: 'app_pt_collect_back_delete', methods: ['POST'])]
    public function delete1(Request $request, PtCollect $ptCollect, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ptCollect->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ptCollect);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pt_collect_back_index', [], Response::HTTP_SEE_OTHER);
    }
}

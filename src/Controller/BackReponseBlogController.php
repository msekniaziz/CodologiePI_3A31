<?php

namespace App\Controller;

use App\Entity\ReponseBlog;
use App\Form\ReponseBlog1Type;
use App\Repository\ReponseBlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/reponse/blog')]
class BackReponseBlogController extends AbstractController
{
    #[Route('/', name: 'app_back_reponse_blog_index', methods: ['GET'])]
    public function index(ReponseBlogRepository $reponseBlogRepository): Response
    {
        return $this->render('back_reponse_blog/index.html.twig', [
            'reponse_blogs' => $reponseBlogRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_reponse_blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponseBlog = new ReponseBlog();
        $form = $this->createForm(ReponseBlog1Type::class, $reponseBlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponseBlog);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_reponse_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_reponse_blog/new.html.twig', [
            'reponse_blog' => $reponseBlog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_reponse_blog_show', methods: ['GET'])]
    public function show(ReponseBlog $reponseBlog): Response
    {
        return $this->render('back_reponse_blog/show.html.twig', [
            'reponse_blog' => $reponseBlog,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_reponse_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReponseBlog $reponseBlog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseBlog1Type::class, $reponseBlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_reponse_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_reponse_blog/edit.html.twig', [
            'reponse_blog' => $reponseBlog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_reponse_blog_delete', methods: ['POST'])]
    public function delete(Request $request, ReponseBlog $reponseBlog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponseBlog->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reponseBlog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_reponse_blog_index', [], Response::HTTP_SEE_OTHER);
    }
}

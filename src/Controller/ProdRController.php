<?php

namespace App\Controller;

use App\Entity\ProdR;
use App\Entity\User;
use App\Form\ProdRType;
use App\Repository\ProdRRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
// use Symfony\Component\HttpFoundation\File\UploadedFile;
// use Symfony\Component\Form\Extension\Core\DataTransformer\FileToStringTransformer;
// use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
// use Oneup\UploaderBundle\Uploader\OrphanageUploader;

// use Symfony\Component\DependencyInjection\ContainerInterface;


#[Route('/prod/r')]
class ProdRController extends AbstractController
{


    #[Route('/', name: 'app_prod_r_index', methods: ['GET'])]
    public function index(ProdRRepository $prodRRepository): Response
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur est authentifié
        if ($user) {
            // Récupérer les dons de l'utilisateur authentifié
            $prodR = $prodRRepository->findBy(['user_id' => $user]);
        } else {
            // Gérer le cas où aucun utilisateur n'est authentifié
            // Par exemple, rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }


        return $this->render('prod_r/index.html.twig', [
            'prod_rs' => $prodRRepository->findBy(['user_id' => $user]),
        ]);
    }

    #[Route('/new', name: 'app_prod_r_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prodR = new ProdR();
        $form = $this->createForm(ProdRType::class, $prodR);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $prodR->setUserId($user);
            // /** @var UploadedFile $imageFile */
            // $imageFile = $form->get('justificatif')->getData();

            // if ($imageFile) {
            // $newFilename = uniqid() . '.' . $imageFile->guessExtension();


            // $prodR->setJustificatif($newFilename);



            $entityManager->persist($prodR);
            $entityManager->flush();

            return $this->redirectToRoute('app_prod_r_index');




            // return $this->redirectToRoute('app_prod_r_index');
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
        if ($this->isCsrfTokenValid('delete' . $prodR->getId(), $request->request->get('_token'))) {
            $entityManager->remove($prodR);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prod_r_index');
    }

    // _______________________________________________________________________________
    // _______________________________________________________________________________
    // _______________________________________________________________________________
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
        $form = $this->createForm(ProdRType::class, $prodR);
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
        $form = $this->createForm(ProdRType::class, $prodR);
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

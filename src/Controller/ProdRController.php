<?php

namespace App\Controller;

use App\Entity\ProdR;
use App\Entity\User;
use App\Form\ProdRType;
use App\Repository\ProdRRepository;
use App\Repository\UserRepository;
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
use App\Repository\TypeDispoRepository;


#[Route('/prod/r')]
class ProdRController extends AbstractController
{
    #[Route('/{idUser}', name: 'app_prod_r_index', methods: ['GET'])]
    public function index(TypeDispoRepository $typeDispoRepository, ProdRRepository $prodRRepository,UserRepository $repository , $idUser): Response
    {
        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }

        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }

        $typeDispos = $typeDispoRepository->findAll();

        // Vérifier si l'utilisateur est authentifié
        if ($user_verif) {
            // Récupérer les dons de l'utilisateur authentifié
            $prodR = $prodRRepository->findBy(['user_id' => $user_verif]);
        } else {
            // Gérer le cas où aucun utilisateur n'est authentifié
            // Par exemple, rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('prod_r/index.html.twig', [
            'prod_rs' => $prodRRepository->findBy(['user_id' => $user_verif->getId()]),
            'typeDispos' => $typeDispos,
            'idUser' => $idUser // Passer le paramètre idUser à la vue
        ]);
    }

    #[Route('/{idUser}/new', name: 'app_prod_r_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserRepository $repository , $idUser): Response
    {
        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }

        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }

        $prodR = new ProdR();
        $form = $this->createForm(ProdRType::class, $prodR);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prodR->setUserId($user_verif);
            $entityManager->persist($prodR);
            $entityManager->flush();

            return $this->redirectToRoute('app_prod_r_index', ['idUser' => $user_verif->getId()]);
        }

        return $this->renderForm('prod_r/new.html.twig', [
            'prod_r' => $prodR,
            'form' => $form,
            'idUser' => $idUser // Passer le paramètre idUser à la vue
        ]);
    }

    #[Route('/edit/{idUser}/{id}', name: 'app_prod_r_edit', methods: ['GET', 'POST'])]
    public function edit($id,Request $request, ProdR $prodR, EntityManagerInterface $entityManager,UserRepository $repository , $idUser): Response
    {
        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createForm(ProdRType::class, $prodR);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_prod_r_index', ['idUser' => $user_verif->getId()]);
        }

        return $this->renderForm('prod_r/edit.html.twig', [
            'prod_r' => $prodR,
            'form' => $form,
            ]);
    }

    #[Route('show/{id}/{idUser}', name: 'app_prod_r_show', methods: ['GET'])]
    public function show(ProdR $prodR, $idUser,$id,UserRepository $repository): Response
    {
        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('prod_r/show.html.twig', [
            'prod_r' => $prodR,
            'idUser' => $idUser // Passer le paramètre idUser à la vue
        ]);
    }

    #[Route('/{idUser}/{id}', name: 'app_prod_r_delete', methods: ['POST'])]
    public function delete(Request $request, ProdR $prodR, EntityManagerInterface $entityManager,UserRepository $repository , $idUser): Response
    {
        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }
        if ($this->isCsrfTokenValid('delete' . $prodR->getId(), $request->request->get('_token'))) {
            $entityManager->remove($prodR);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prod_r_index', ['idUser' => $user_verif->getId()]);
    }}

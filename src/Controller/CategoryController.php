<?php

namespace App\Controller;



use App\Entity\Category;

use App\Form\CategoryType;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{idUser}', name: 'app_category')]
    public function afficheCategory($idUser,UserRepository $repository): Response
    {
        $user = $repository->find($idUser);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $entityManager = $this->getDoctrine()->getRepository(Category::class);
        $category = $entityManager->findAll();
        return $this->render('category/AfficheCat.html.twig', [
            'category' => $category ,
        ]);
    }
     #[Route('/category/add/{id}', name:'new_category')]
    public function addCategory(Request $request,$id,UserRepository $repository): Response
    {
        $user = $repository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        // Création d'une nouvelle instance de Category
        $category = new Category();

        // Création du formulaire lié à l'entité Category
        $form = $this->createForm(CategoryType::class, $category);

        // Traitement de la requête
        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement de la nouvelle catégorie
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            // Redirection vers une autre page (par exemple la page d'accueil)
            return $this->redirectToRoute('app_category', ['idUser' => $user->getId()]);
        }

        // Rendu du template avec le formulaire
        return $this->render('category/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{idUser}/{id}', name: 'deletecategory')]
    public function supprimer($id,$idUser,UserRepository $repository): Response
    {
        $user = $repository->find($idUser);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('app_category', ['idUser' => $user->getId()]);

    }

    #[Route('/category/{idUser}/{id}/edit', name: 'updatecategory')]
    public function editCategory(Request $request, $id,$idUser,UserRepository $repository): Response
    {
        $user = $repository->find($idUser);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_category', ['idUser' => $user->getId()]);
        }
        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }





}

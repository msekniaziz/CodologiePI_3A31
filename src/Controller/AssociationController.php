<?php

namespace App\Controller;

use App\Entity\Association;
use App\Form\AssociationType;
use App\Repository\AssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/association')]
class AssociationController extends AbstractController
{ #[Route('/testback', name: 'appback', methods: ['GET'])]
    public function indextestback(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        return $this->render('back.html.twig');
    }

    #[Route('/', name: 'app_association_index', methods: ['GET'])]
    public function index(AssociationRepository $associationRepository): Response
    {
        
        return $this->render('association/index.html.twig', [
            'associations' => $associationRepository->findAll(),
        ]);
    }
    #[Route('/test', name: 'app_association_indexHomeOn', methods: ['GET'])]
    public function indexHomeOn(AssociationRepository $associationRepository): Response
    { $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        return $this->render('association/indexHomeOn.html.twig', [
            'associations' => $associationRepository->findAll(),
        ]);
    }


    #[Route('/back', name: 'app_association_indexback', methods: ['GET'])]
    public function indexback(AssociationRepository $associationRepository): Response
    { $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        return $this->render('association/indexback.html.twig', [
            'associations' => $associationRepository->findAll(),
        ]);
    }
    
    #[Route('/new', name: 'app_association_new', methods: ['GET', 'POST'])]
     public function new(Request $request, EntityManagerInterface $entityManager): Response
    { $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $association = new Association();
        $form = $this->createForm(AssociationType::class, $association,[
            'edit_mode' => false,
        ]);
       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($association);
            $entityManager->flush();
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('logoAssociation')->getData();
        $entityManager->persist($association);
        $entityManager->flush();
        if ($imageFile) {
                    $newFilename = uniqid() . '.' . $imageFile->guessExtension();
        
                    try {
                        $imageFile->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // Gérer l'exception
                    }
                    $association->setLogoAssociation($newFilename);
        
                    // Flush again to save changes related to the image file
                    $entityManager->flush();
                }
            return $this->redirectToRoute('app_association_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('association/new.html.twig', [
            'association' => $association,
            'form' => $form,
        ]);
    }

    #[Route('/showoff/{id}', name: 'app_association_show', methods: ['GET'])]
    public function show(Association $association): Response
    { 

       
        return $this->render('association/show.html.twig', [
            'association' => $association,
        ]);
    }
    #[Route('/{id}', name: 'app_association_show_On', methods: ['GET'])]
    public function showOn(Association $association): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('association/showOn.html.twig', [
            'association' => $association,
        ]);
    }

    #[Route('/back/{id}', name: 'app_association_showback', methods: ['GET'])]
    public function showback(Association $association): Response
    {
        return $this->render('association/showback.html.twig', [
            'association' => $association,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_association_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Association $association, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createForm(AssociationType::class, $association, [
            'edit_mode' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_association_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('association/edit.html.twig', [
            'association' => $association,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_association_delete', methods: ['POST'])]
    public function delete(Request $request, Association $association, EntityManagerInterface $entityManager): Response
    { $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        if ($this->isCsrfTokenValid('delete'.$association->getId(), $request->request->get('_token'))) {
            $entityManager->remove($association);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_association_indexback', [], Response::HTTP_SEE_OTHER);
    }
}

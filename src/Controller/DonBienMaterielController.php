<?php

namespace App\Controller;

use App\Entity\DonBienMateriel;

use App\Form\DonBienMaterielType;
use App\Repository\AssociationRepository;
// use App\Form\backDonBienMaterielType;
use App\Repository\DonBienMaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/don/bien/materiel')]
class DonBienMaterielController extends AbstractController
{
    #[Route('/', name: 'app_don_bien_materiel_index', methods: ['GET'])]
    public function index(DonBienMaterielRepository $donBienMaterielRepository): Response
    {
        // Récupérer l'utilisateur actuellement authentifié
        $user = $this->getUser();


        // Vérifier si l'utilisateur est authentifié
        if ($user) {
            // Récupérer les dons de l'utilisateur authentifié
            $don_bien_materiels = $donBienMaterielRepository->findBy(['user_id' => $user]);
        } else {
            // Gérer le cas où aucun utilisateur n'est authentifié
            // Par exemple, rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('don_bien_materiel/index.html.twig', [
            'don_bien_materiels' => $don_bien_materiels,
        ]);
    }

    #[Route('/back', name: 'app_don_bien_materiel_indexback', methods: ['GET'])]
    public function indexback(DonBienMaterielRepository $donBienMaterielRepository): Response
    {
        return $this->render('don_bien_materiel/indexback.html.twig', [
            'don_bien_materiels' => $donBienMaterielRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_don_bien_materiel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AssociationRepository $associationsRepository): Response
    {
        $user = $this->getUser();
        $associationId = $request->attributes->get('id');
        $association = $associationsRepository->find($associationId);
    
        $donBienMateriel = new DonBienMateriel();
        $donBienMateriel->setIdAssociation($association);
    
        $form = $this->createForm(DonBienMaterielType::class, $donBienMateriel, [
            'edit_mode' => false,
            
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $donBienMateriel->setUserId($user);
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('photoDon')->getData();
    
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception
                }
    
                $donBienMateriel->setPhotoDon($newFilename);
            }
    
            $entityManager->persist($donBienMateriel);
            $entityManager->flush();
            // $this->addFlash('success', 'Account created ! Check Your Mail For Verification');
            return $this->redirectToRoute('app_don_bien_materiel_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('don_bien_materiel/new.html.twig', [
            'don_bien_materiel' => $donBienMateriel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_bien_materiel_show', methods: ['GET'])]
    public function show(DonBienMateriel $donBienMateriel): Response
    {
        return $this->render('don_bien_materiel/show.html.twig', [
            'don_bien_materiel' => $donBienMateriel,
        ]);
    }

    #[Route('/back/{id}', name: 'app_don_bien_materiel_showback', methods: ['GET'])]
    public function showback(DonBienMateriel $donBienMateriel): Response
    {
        return $this->render('don_bien_materiel/showback.html.twig', [
            'don_bien_materiel' => $donBienMateriel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_don_bien_materiel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DonBienMateriel $donBienMateriel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonBienMaterielType::class, $donBienMateriel,[
            'edit_mode' => true,
        ]);  
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_don_bien_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don_bien_materiel/edit.html.twig', [
            'don_bien_materiel' => $donBienMateriel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_bien_materiel_delete', methods: ['POST'])]
    public function delete(Request $request, DonBienMateriel $donBienMateriel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donBienMateriel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($donBienMateriel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_don_bien_materiel_index', [], Response::HTTP_SEE_OTHER);
    }
}

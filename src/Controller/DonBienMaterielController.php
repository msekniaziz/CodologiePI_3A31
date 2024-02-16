<?php

namespace App\Controller;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use App\Entity\DonBienMateriel;
use App\Form\DonBienMaterielType;
// use App\Form\backDonBienMaterielType;
use App\Repository\DonBienMaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/don/bien/materiel')]
class DonBienMaterielController extends AbstractController
{
    #[Route('/', name: 'app_don_bien_materiel_index', methods: ['GET'])]
    public function index(DonBienMaterielRepository $donBienMaterielRepository): Response
    {
        return $this->render('don_bien_materiel/index.html.twig', [
            'don_bien_materiels' => $donBienMaterielRepository->findAll(),
        ]);
    }

    #[Route('/back', name: 'app_don_bien_materiel_indexback', methods: ['GET'])]
    public function indexback(DonBienMaterielRepository $donBienMaterielRepository): Response
    {
        return $this->render('don_bien_materiel/indexback.html.twig', [
            'don_bien_materiels' => $donBienMaterielRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_don_bien_materiel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {   $user = $this->getUser();
        if (!$user) {
            // L'utilisateur n'est pas connecté, une exception se génère
             throw new AuthenticationException('Désolé, vous devez être identifié pour accéder à cette page.');
           
        }
        return $this->redirectToRoute('app_login'); // Rediriger vers la page de connexion
            
       
            $donBienMateriel = new DonBienMateriel();
        $form = $this->createForm(DonBienMaterielType::class, $donBienMateriel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donBienMateriel->setUserId($user);
       
            $entityManager->persist($donBienMateriel);
            $entityManager->flush();

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

    #[Route('/{id}/edit', name: 'app_don_bien_materiel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DonBienMateriel $donBienMateriel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonBienMaterielType::class, $donBienMateriel);
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

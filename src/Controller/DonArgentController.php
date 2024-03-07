<?php

namespace App\Controller;

use App\Entity\DonArgent;

use App\Form\DonArgentType;
use App\Repository\AssociationRepository;
// use App\Form\backdonArgentType;
use App\Repository\DonArgentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;




#[Route('/don/argent')]
class DonArgentController extends AbstractController
{
    #[Route('/', name: 'app_don_argent_index', methods: ['GET'])]
    public function index(DonArgentRepository $donArgentRepository): Response
    {
        // Récupérer l'utilisateur actuellement authentifié
        $user = $this->getUser();


        // Vérifier si l'utilisateur est authentifié
        if ($user) {
            // Récupérer les dons de l'utilisateur authentifié
            $don_argent = $donArgentRepository->findBy(['user_id' => $user]);
        } else {
            // Gérer le cas où aucun utilisateur n'est authentifié
            // Par exemple, rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('don_argent/index.html.twig', [
            'don_argent' => $don_argent,
        ]);
    }

    #[Route('/back', name: 'app_don_argent_indexback', methods: ['GET'])]
    public function indexback(donArgentRepository $donArgentRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('don_argent/indexback.html.twig', [
            'don_argent' => $donArgentRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_don_argent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AssociationRepository $associationsRepository,UrlGeneratorInterface $urlGenerator): Response
    {
 $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }        $associationId = $request->attributes->get('id');
        $association = $associationsRepository->find($associationId);
    
        $donArgent = new donArgent();
        $donArgent->setIdAssociation($association);
    
        $form = $this->createForm(donArgentType::class, $donArgent);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
           
            $donArgent->setUserId($user);
            $entityManager->persist($donArgent);
            $entityManager->flush();
             // Générer les URLs des deux destinations
        $checkoutUrl = $urlGenerator->generate('checkout', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $donArgentIndexUrl = $urlGenerator->generate('app_don_argent_index', [], UrlGeneratorInterface::ABSOLUTE_URL);
        
        // Générer le script JavaScript pour ouvrir deux onglets
        $script = sprintf('<script>window.open("%s", "_blank");window.open("%s", "_blank");</script>', $checkoutUrl, $donArgentIndexUrl);
        
        // Retourner la réponse contenant le script JavaScript
        return new Response($script);
            // $this->addFlash('success', 'Account created ! Check Your Mail For Verification');
            // return $this->redirectToRoute('checkout', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('don_argent/new.html.twig', [
            'don_argent' => $donArgent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_argent_show', methods: ['GET'])]
    public function show(donArgent $donArgent): Response
    { $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        return $this->render('don_argent/show.html.twig', [
            'don_argent' => $donArgent,
        ]);
    }

    #[Route('/back/{id}', name: 'app_don_argent_showback', methods: ['GET'])]
    public function showback(donArgent $donArgent): Response
    { $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        return $this->render('don_argent/showback.html.twig', [
            'don_argent' => $donArgent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_don_argent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, donArgent $donArgent, EntityManagerInterface $entityManager): Response
    { $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $form = $this->createForm(donArgentType::class, $donArgent);  
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_don_argent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don_argent/edit.html.twig', [
            'don_argent' => $donArgent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_argent_delete', methods: ['POST'])]
    public function delete(Request $request, donArgent $donArgent, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        if ($this->isCsrfTokenValid('delete'.$donArgent->getId(), $request->request->get('_token'))) {
            $entityManager->remove($donArgent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_don_argent_index', [], Response::HTTP_SEE_OTHER);
    }
}

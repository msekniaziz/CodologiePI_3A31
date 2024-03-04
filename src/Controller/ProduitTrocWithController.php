<?php

namespace App\Controller;

use App\Entity\ProduitTrocWith;
use App\Form\ProduitTrocWithType;
use App\Repository\ProduitTrocWithRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

use App\Entity\ProduitTroc;
use App\Repository\ProduitTrocRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/produit/troc/with')]
class ProduitTrocWithController extends AbstractController
{
    private $logger;

    // Constructor to inject LoggerInterface
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    #[Route('/s', name: 'app_produit_troc_with_index', methods: ['GET'])]
    public function index(ProduitTrocWithRepository $produitTrocWithRepository): Response
    {
        return $this->render('produit_troc_with/index1.html.twig', [
            'produit_troc_withs' => $produitTrocWithRepository->findAll(),
        ]);
    }
    #[Route('/si', name: 'app_produit_troc_with_404', methods: ['GET'])]
    public function index404(ProduitTrocWithRepository $produitTrocWithRepository): Response
    {
        return $this->render('produit_troc_with/404Exg.html.twig');
    }

    #[Route('/ba', name: 'app_produit_troc_with_index_back', methods: ['GET'])]
    public function indexback(ProduitTrocWithRepository $produitTrocWithRepository): Response
    {
        return $this->render('prod_troc_w.html.twig', [
            'produit_trocs' => $produitTrocWithRepository->findAll(),
        ]);
    }
    #[Route('/new/{id}', name: 'app_produit_troc_with_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ProduitTrocRepository $produitTrocRepository): Response
    {
        $user = $this->getUser();
        
        // Retrieve the ID of the produit troc from the request
        $produitTrocId = $request->attributes->get('id');
    
        // Retrieve the ProduitTroc entity based on the ID
        $produitTroc = $produitTrocRepository->find($produitTrocId);
    
        // Check if the ProduitTroc entity exists
        if (!$produitTroc) {
            // Render the 404 page if the ProduitTroc entity is not found
            return $this->redirectToRoute('app_produit_troc_with_404');
        }
    
        // Create a new instance of ProduitTrocWith and associate it with the ProduitTroc entity
        $produitTrocWith = new ProduitTrocWith();
        $produitTrocWith->setProdIdTroc($produitTroc);
    
        // Create the form using ProduitTrocWithType
        $form = $this->createForm(ProduitTrocWithType::class, $produitTrocWith);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Handle form submission
                // Handle file upload
                $imageFile = $form->get('image')->getData();
                if ($imageFile) {
                    $newFilename = uniqid().'.'.$imageFile->guessExtension();
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    // Set the image filename in the entity
                    $produitTrocWith->setImage($newFilename);
                }
    
                // Persist the entity
                $entityManager->persist($produitTrocWith);
                $entityManager->flush();
    
                // Redirect after successful submission
                return $this->redirectToRoute('app_produit_troc_with_show', [
                    'id' => $produitTrocWith->getId(),
                ]);
            } catch (\Exception $e) {
                // An error occurred during database operation
                // Log the error if needed
                $this->logger->error('An error occurred: ' . $e->getMessage());
                
                // Redirect to 404 page
                return $this->redirectToRoute('app_produit_troc_with_404');
            }
        }
    
        // Render the form
        return $this->render('produit_troc_with/new1.html.twig', [
            'form' => $form->createView(),
            'id' => $produitTrocId,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_produit_troc_with_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProduitTrocWith $produitTrocWith, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitTrocWithType::class, $produitTrocWith);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
          
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                   
                    $this->addFlash('error', 'Failed to upload image.');
                    return $this->redirectToRoute('app_produit_troc_with_edit', ['id' => $produitTrocWith->getId()]);
                }
                $produitTrocWith->setImage($newFilename);
            }
    
            // Persist the entity
            $entityManager->persist($produitTrocWith);
            $entityManager->flush();
    
            // Redirect after successful submission
            return $this->redirectToRoute('app_produit_troc_with_show', [
                'id' => $produitTrocWith->getId(),
            ]);        }
    
        // Render the form
        return $this->renderForm('produit_troc_with/edit1.html.twig', [
            'produit_troc_with' => $produitTrocWith,
            'form' => $form,
        ]);
    }
        

#[Route('/{id}', name: 'app_produit_troc_with_show', methods: ['GET'])]
public function show(ProduitTrocWith $produitTrocWith): Response
{
    return $this->render('produit_troc_with/show.html.twig', [
        'produit_troc_with' => $produitTrocWith,
    ]);
}
    #[Route('/{id}', name: 'app_produit_troc_with_delete', methods: ['POST'])]
    public function delete(Request $request, ProduitTrocWith $produitTrocWith, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produitTrocWith->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produitTrocWith);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_produit_troc_with_index', [], Response::HTTP_SEE_OTHER);

    }


}

<?php

namespace App\Controller;
use App\Entity\ProduitTrocWith;

use App\Entity\ProduitTroc;
use App\Form\ProduitTroc1Type;
use App\Repository\ProduitTrocRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Exception\FileException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/produit/troc')]
class ProduitTrocController extends AbstractController
{
    #[Route('/', name: 'app_produit_troc_index', methods: ['GET'])]
    public function index(ProduitTrocRepository $produitTrocRepository): Response
    {
        return $this->render('index1.html.twig', [
            'produit_trocs' => $produitTrocRepository->findAll(),
        ]);
    }
    #[Route('/l', name: 'app_produit_troc_indexES', methods: ['GET'])]
    public function indexEd(ProduitTrocRepository $produitTrocRepository): Response
    {
        return $this->render('indexES.html.twig', [
            'produit_trocs' => $produitTrocRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_troc_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();
    $produitTroc = new ProduitTroc();
    $form = $this->createForm(ProduitTroc1Type::class, $produitTroc);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('images_directory'), // Destination directory, should be defined in the configuration file
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle the exception, such as logging an error message or displaying a user-friendly error
                echo 'An error occurred: ' . $e->getMessage();
                // You might want to return an error response here
            }

            $produitTroc->setImage($newFilename);
            $produitTroc->setStatut(0); // Use setStatut instead of getStatut
            $produitTroc->setIdUser($user);
        }var_dump($imageFile);

        $entityManager->persist($produitTroc);
        $entityManager->flush();

        return $this->redirectToRoute('app_produit_troc_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('produit_troc/new1.html.twig', [
        'produit_troc' => $produitTroc,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_produit_troc_show', methods: ['GET'])]
    public function show(ProduitTroc $produitTroc): Response
    {
        return $this->render('produit_troc/show.html.twig', [
            'produit_troc' => $produitTroc,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_troc_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProduitTroc $produitTroc, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitTroc1Type::class, $produitTroc);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('image')->getData();
    
            // Debugging: Dump the image file data
            dump($imageFile);
    
            // Check if a new image was uploaded
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // You may want to log the error or display a user-friendly message
                    // and return a response indicating failure
                    // For example:
                    $this->addFlash('error', 'Failed to upload image.');
                    return $this->redirectToRoute('app_produit_troc_edit', ['id' => $produitTroc->getId()]);
                }
    
                // Remove the old image file if it exists
                $oldFilename = $produitTroc->getImage();
                if ($oldFilename) {
                    unlink($this->getParameter('images_directory').'/'.$oldFilename);
                }
    
                // Set the new image filename
                $produitTroc->setImage($newFilename);
            }
    
            // Persist changes to the entity
            $entityManager->flush();
    
            // Redirect to the index page after successful edit
            return $this->redirectToRoute('app_produit_troc_index');
        }
    
        // Render the edit form
        return $this->renderForm('produit_troc/edit1.html.twig', [
            'produit_troc' => $produitTroc,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_produit_troc_delete', methods: ['POST'])]
    public function delete(Request $request, ProduitTroc $produitTroc, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produitTroc->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produitTroc);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_troc_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('produit_troc_with/{id}', name: 'app_produit_ajoutpttocw', methods: ['POST'])]
    public function affichajout(Request $request, ProduitTrocWith $produitTroc, EntityManagerInterface $entityManager): Response
    {
        return $this->render('produit_troc_with/new.html.twig', [
            'produit_troc_with' => $produitTroc,
        ]); 

    }
}

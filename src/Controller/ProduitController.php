<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;


use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\DataTransformer\FileToStringTransformer;


class ProduitController extends AbstractController
{
    #[Route('/produit',name:'app_produit')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
    #[Route('/produit/new', name: 'new_annonces')]
    public function addAnnonce(Request $request): Response
    {
        $annonce = new Annonces();
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'), // Répertoire de destination, à définir dans le fichier de configuration
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $annonce->setImage($newFilename);
                $annonce->setStatus(0);
            }

            // Sauvegarder l'annonce
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('new_annonces');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer la valeur du champ "Negotiable"
            $negotiable = $form->get('Negociable')->getData();

            // Si le champ n'est pas coché, ajuster la valeur à 0
            if ($negotiable === null) {
                $negotiable = 0;
            }

            // Ajuster la valeur en conséquence
            $annonce->setNegociable($negotiable);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('mes_annonces_utilisateur_1');
        }


        return $this->render('produit/index.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/produit/affiche', name: 'aff_annonces')]
    public function allAnnonces(AnnoncesRepository $annonceRepository) : Response
    {
        $entityManager = $this->getDoctrine()->getRepository(Annonces::class);
        $annonces = $entityManager->findAll();
        $countHouse = $annonceRepository->countByCategoryHouse();
        $countGarden = $annonceRepository->countByCategoryGarden();
        return $this->render('produit/affiche.html.twig', [
            'annonces' => $annonces,'countHouse' => $countHouse,
            'countGarden' => $countGarden,

        ]);
    }


    #[Route('/mes-annonces', name: 'mes_annonces_utilisateur_1')]
    public function mesAnnoncesUtilisateur1(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Récupérez les annonces de l'utilisateur avec l'ID 1
        $annonces = $entityManager->getRepository(Annonces::class)->findBy(['user' => 1]);

        // Passez les annonces au template Twig
        return $this->render('produit/mes_annonces.html.twig', [
            'annonces' => $annonces,
        ]);
    }
    #[Route('/delete/{id}', name: 'deleteannonce')]
    public function supprimer($id): Response
    {
        $annonce = $this->getDoctrine()->getRepository(Annonces::class)->find($id);

        if (!$annonce) {
            throw $this->createNotFoundException('Annonce non trouvée');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($annonce);
        $entityManager->flush();
        return $this->redirectToRoute('mes_annonces_utilisateur_1');


    }


    #[Route('/produit/{id}/edit', name: 'updateannonce')]
    public function editAnnonce(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $annonce = $entityManager->getRepository(Annonces::class)->find($id);
        $form = $this->createForm(AnnoncesType::class, $annonce);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les fichiers image
            $imageFiles = $form->get('image')->getData();

            // Traiter chaque fichier image
            foreach ($imageFiles as $imageFile) {
                // Générer un nouveau nom de fichier unique
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Déplacer le fichier vers le répertoire de destination
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception si nécessaire
                }

                // Ajouter le nom du fichier à l'annonce
                $annonce->addImage($newFilename);
            }

            // Mettre à jour d'autres champs si nécessaire
            // ...

            // Sauvegarder l'annonce
            $entityManager->persist($annonce);
            $entityManager->flush();

            // Rediriger après la sauvegarde
            return $this->redirectToRoute('mes_annonces_utilisateur_1');
        }

        return $this->render('produit/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}



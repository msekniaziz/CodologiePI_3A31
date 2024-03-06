<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\AnnoncesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\DataTransformer\FileToStringTransformer;
use Knp\Component\Pager\PaginatorInterface;



class ProduitController extends AbstractController
{
    #[Route('/produit',name:'app_produit')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
    #[Route('/new/{idUser}', name: 'new_annonces')]
    public function addAnnonce(Request $request,UserRepository $repository,$idUser): Response
    {
        $user=$repository->find($idUser);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $annonce = new Annonces();
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser(); // Récupérer l'utilisateur actuellement connecté
            $category = $annonce->getIdCat();
            $category->setNbrAnnonce($category->getNbrAnnonce() + 1);
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception
                }

                $annonce->setImage($newFilename);
            }

            $annonce->setStatus(0);
            $annonce->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Congratulations ! your product will be soon confirmed ');
            return $this->redirectToRoute('new_annonces',['idUser'=>$user->getId()]);
        }

        return $this->render('produit/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/statistiques', name: 'annonces_statistiques')]
    public function annoncesStatistiques(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $annoncesActives = $entityManager->getRepository(Annonces::class)->count(['status' => 1]);

        $annoncesNonActives = $entityManager->getRepository(Annonces::class)->count(['status' => 0]);

        return $this->render('produit/stat.html.twig', [
            'annoncesActives' => $annoncesActives,
            'annoncesNonActives' => $annoncesNonActives,
        ]);
    }

    #[Route('/produit/affiche/{id}', name: 'aff_annonces')]
    public function allAnnonces(Request $request ,UserRepository $repository, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager = $this->getDoctrine()->getManager();
        $orderBy = $request->query->get('orderBy');
        if ($orderBy == 'asc') {
            $annonces = $entityManager->getRepository(Annonces::class)->findBy(['category' => 'active'], ['prix' => 'ASC']);
        } elseif ($orderBy == 'desc') {
            $annonces = $entityManager->getRepository(Annonces::class)->findBy(['category' => 'active'], ['prix' => 'DESC']);
        } else {
            $annonces = $entityManager->getRepository(Annonces::class)->findBy(['category' => 'active']);
        }

        return $this->render('produit/affiche.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    #[Route('/produit/afficheback/{id}', name: 'all_announceAdmin')]
    public function allAnnoncesAdmin($id,UserRepository $repository) : Response
    {
        $user = $repository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $entityManager = $this->getDoctrine()->getRepository(Annonces::class);
        $annonces = $entityManager->findAll();
        return $this->render('produit/affAdBack.html.twig', [
            'annonces' => $annonces,
        ]);
    }
    #[Route('/mes-annonces/{id}', name: 'mes_annonces_utilisateur_1')]
    public function mesAnnoncesUtilisateur1(FlashyNotifier $flashy, SessionInterface $session , UserRepository $repository,$id): Response
    {
        // $user = $this->getUser(); // Récupérer l'utilisateur actuel
        $user = $repository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $entityManager = $this->getDoctrine()->getManager();

        // Récupérez les annonces de l'utilisateur actuel
        $annonces = $entityManager->getRepository(Annonces::class)->findBy(['user' => $user]);

        $isNewUserAdded = $session->get('commande_success', false);
        if ($isNewUserAdded) {
            $notificationLink = 'a new user is added';
            $flashy->success('A new user is added', $notificationLink);
            $session->set('isNewUserAdded', false);
        }
        // $isNewCommandeAdded = $session->get('commande_success', false);
        /*  if ($isNewCommandeAdded) {
              $annonceId = $session->get('user_id');
              if ($annonceId == $id) {
                  $notificationMessage = "Commande ajoutée pour l'annonce : ";
                  $flashy->success($notificationMessage);
              }
              $session->remove('commande_success');
              $session->remove('user_id'); // Supprimer également l'ID de l'utilisateur de la session
          }
            */

        // Passez les annonces au template Twig
        return $this->render('produit/mes_annonces.html.twig', [
            'annonces' => $annonces,

        ]);
    }


    #[Route('/delete1/{id}', name: 'deleteannonce')]
    public function supprimer($id): Response
    {
        $annonce = $this->getDoctrine()->getRepository(Annonces::class)->find($id);

        if (!$annonce) {
            throw $this->createNotFoundException('Annonce non trouvée');
        }
        $category = $annonce->getIdCat();
        $category->setNbrAnnonce($category->getNbrAnnonce() - 1);
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

        if (!$annonce) {
            throw $this->createNotFoundException('Annonce non trouvée');
        }
        $oldCategory = $annonce->getIdCat();
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception, si nécessaire
                }

                $annonce->setImage($newFilename);
            }

            // Sauvegarder l'annonce
            $entityManager->flush();
            $newCategory = $annonce->getIdCat();
            if ($oldCategory !== $newCategory) {
                // Mettre à jour le nombre d'annonces dans la catégorie précédente
                $oldCategory->setNbrAnnonce($oldCategory->getNbrAnnonce() - 1);
                $entityManager->persist($oldCategory);

                // Mettre à jour le nombre d'annonces dans la nouvelle catégorie
                $newCategory->setNbrAnnonce($newCategory->getNbrAnnonce() + 1);
                $entityManager->persist($newCategory);

                $entityManager->flush();
            }
            return $this->redirectToRoute('new_annonces');
        }

        return $this->render('produit/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/testback', name: 'appback', methods: ['GET'])]
    public function indextestback(): Response
    {
        return $this->render('back.html.twig');
    }

    #[Route('/delete1Back/{id}', name: 'deleteannonceBack')]
    public function supprimerBack($id): Response
    {
        $annonce = $this->getDoctrine()->getRepository(Annonces::class)->find($id);

        if (!$annonce) {
            throw $this->createNotFoundException('Annonce non trouvée');
        }
        $category = $annonce->getIdCat();
        $category->setNbrAnnonce($category->getNbrAnnonce() - 1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($annonce);
        $entityManager->flush();
        return $this->redirectToRoute('all_announceAdmin');


    }


    #[Route('/produit/{id}/editBack', name: 'updateannonceBack')]
    public function editAnnonceBack(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $annonce = $entityManager->getRepository(Annonces::class)->find($id);

        if (!$annonce) {
            throw $this->createNotFoundException('Annonce non trouvée');
        }
        $oldCategory = $annonce->getIdCat();
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception, si nécessaire
                }

                $annonce->setImage($newFilename);
            }

            // Sauvegarder l'annonce
            $entityManager->flush();
            $newCategory = $annonce->getIdCat();
            if ($oldCategory !== $newCategory) {
                // Mettre à jour le nombre d'annonces dans la catégorie précédente
                $oldCategory->setNbrAnnonce($oldCategory->getNbrAnnonce() - 1);
                $entityManager->persist($oldCategory);

                // Mettre à jour le nombre d'annonces dans la nouvelle catégorie
                $newCategory->setNbrAnnonce($newCategory->getNbrAnnonce() + 1);
                $entityManager->persist($newCategory);

                $entityManager->flush();
            }
            return $this->redirectToRoute('all_announceAdmin');
        }

        return $this->render('produit/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/active/{id}/{idActive}', name: 'product_active')]
    public function prod_active(UserRepository $repository1, $id, AnnoncesRepository $repository, EntityManagerInterface $entityManager, $idActive): Response
    {
        $user = $repository1->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        if (!$idActive) {
            throw $this->createNotFoundException('No ID found');
        }

        $annonceActive = $repository->find($idActive);
        if (!$annonceActive) {
            throw $this->createNotFoundException('Announcement not found');
        }
        $annonceActive->setCategory("active");
        $entityManager->flush();

        return $this->redirectToRoute('all_announceAdmin', ['id' => $user->getId()]);
    }





}


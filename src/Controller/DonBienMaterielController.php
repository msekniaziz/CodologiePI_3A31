<?php

namespace App\Controller;

use App\Entity\DonBienMateriel;
use App\Entity\User;

use App\Form\DonBienMaterielType;
use App\Repository\AssociationRepository;
use App\Form\backDonBienMaterielType;
use App\Repository\DonBienMaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/don/bien/materiel')]
class DonBienMaterielController extends AbstractController
{
//     #[Route('/', name: 'app_don_bien_materiel_index', methods: ['GET'])]
// public function index(Request $request, DonBienMaterielRepository $donBienMaterielRepository, PaginatorInterface $paginator): Response
// {
//     // Récupérer l'utilisateur actuellement authentifié
//     $user = $this->getUser();

//     // Vérifier si l'utilisateur est authentifié
//     if ($user) {
//         // Récupérer les dons de l'utilisateur authentifié
//         $query= $donBienMaterielRepository->findBy(['user_id' => $user]);
        
//         // Paginate the query
//         $don_bien_materiels = $paginator->paginate(
//             $query, // Query to paginate
//             $request->query->getInt('page', 1), // Get page parameter from the request, default to 1
//             10 // Number of items per page
//         );
//     } else {
//         // Gérer le cas où aucun utilisateur n'est authentifié
//         // Par exemple, rediriger vers la page de connexion
//         return $this->redirectToRoute('app_login');
//     }

//     return $this->render('don_bien_materiel/index.html.twig', [
//         'don_bien_materiels' => $don_bien_materiels,
//     ]);
// }
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

//     #[Route('/back', name: 'app_don_bien_materiel_indexback', methods: ['GET'])]
// public function indexback(Request $request, DonBienMaterielRepository $donBienMaterielRepository): Response
// {
//     $term = $request->query->get('term');

//     if ($term) {
//         // Si un terme de recherche est fourni, recherchez les dons correspondants
//         $donBienMateriels = $donBienMaterielRepository->findByTerm($term);
//     } else {
//         // Sinon, récupérez tous les dons
//         $donBienMateriels = $donBienMaterielRepository->findAll();
//     }

//     return $this->render('don_bien_materiel/indexback.html.twig', [
//         'don_bien_materiels' => $donBienMateriels,
//     ]);
// }


    #[Route('/back', name: 'app_don_bien_materiel_indexback', methods: ['GET'])]
    public function indexback(DonBienMaterielRepository $donBienMaterielRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('don_bien_materiel/indexback.html.twig', [
            'don_bien_materiels' => $donBienMaterielRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_don_bien_materiel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AssociationRepository $associationsRepository): Response
    {
 $User = $this->getUser();

        if (!$User) {
            throw $this->createNotFoundException('User not found');
        }        $associationId = $request->attributes->get('id');
        $association = $associationsRepository->find($associationId);
    
        $donBienMateriel = new DonBienMateriel();
        $donBienMateriel->setIdAssociation($association);
    
        $form = $this->createForm(DonBienMaterielType::class, $donBienMateriel, [
            'edit_mode' => false,
            
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $donBienMateriel->setUserId($User);
            if ($User instanceof User) {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->Username = 'wellness2a@outlook.com'; // Votre adresse Gmail
            $mail->Password = '2A27wellness'; // Votre mot de passe Gmail

            // Sender and recipient settings
            $mail->setFrom('wellness2a@outlook.com', 'JARDIN DART');
            $nomUser = $User->getPrenom();

            $emailUser = $User->getMail();
            $mail->addAddress($emailUser);
            $mail->isHTML(true);
            $mail->Subject = 'DONATION APPROVED!';
            $mail->Body = "Dear $nomUser , <br>
            Your donation has been approved , please stay up to date we will call you later

<br> Thank you for your trust. <br>

The Codologie Team";
            $mail->AltBody = "hi";

            $mail->send();
           
           
            }
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
            // 'don_bien_materiel' => $donBienMateriel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_bien_materiel_show', methods: ['GET'])]
    public function show(DonBienMateriel $donBienMateriel): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('don_bien_materiel/show.html.twig', [
            'don_bien_materiel' => $donBienMateriel,
        ]);
    }

    #[Route('/back/{id}', name: 'app_don_bien_materiel_showback', methods: ['GET'])]
    public function showback(DonBienMateriel $donBienMateriel): Response
    {  $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        return $this->render('don_bien_materiel/showback.html.twig', [
            'don_bien_materiel' => $donBienMateriel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_don_bien_materiel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DonBienMateriel $donBienMateriel, EntityManagerInterface $entityManager): Response
    {  $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
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
    #[Route('/{id}/editback', name: 'app_don_bien_materiel_edit_back', methods: ['GET', 'POST'])]
    public function editback(Request $request, DonBienMateriel $donBienMateriel, EntityManagerInterface $entityManager): Response
    { $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $form = $this->createForm(backDonBienMaterielType::class, $donBienMateriel);  
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $userId = $donBienMateriel->getUserId();

            $User = $entityManager->getRepository(User::class)->find($userId);
            if ($User instanceof User) {

                if ($donBienMateriel->isStatutDon() == true) {


                    $nbUp = $User->getNbPoints() + 1;
                }else{
                    $nbUp = $User->getNbPoints() - 1;

                }
                $User->setNbPoints($nbUp);
                $entityManager->persist($User);
                $entityManager->flush();
            return $this->redirectToRoute('app_don_bien_materiel_indexback', [], Response::HTTP_SEE_OTHER);
        }
    }
        return $this->renderForm('don_bien_materiel/editback.html.twig', [
            'don_bien_materiel' => $donBienMateriel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_bien_materiel_delete', methods: ['POST'])]
    public function delete(Request $request, DonBienMateriel $donBienMateriel, EntityManagerInterface $entityManager): Response
    { $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        if ($this->isCsrfTokenValid('delete'.$donBienMateriel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($donBienMateriel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_don_bien_materiel_index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/search', name: 'don_search', methods: ['GET'])]
    // public function search(Request $request, DonBienMaterielRepository $repository): JsonResponse
    // {
    //     $term = $request->query->get('term');
    //     $results = $repository->findByTerm($term); // nhotha fel repo

    //     $data = [];
    //     foreach ($results as $result) {
    //         $data[] = [
    //             'id' => $result->getId(),
    //             'label' => $result->getDescriptionDon(), // Utilisez la description du don comme libellé pour l'autocomplétion
    //         ];
    //     }

    //     return new JsonResponse($data);
    // }



}

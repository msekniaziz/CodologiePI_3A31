<?php

namespace App\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';
use App\Controller\ProdRController;
use App\Entity\ProdR;
use App\Entity\User;
// use App\Form\ProdRType;
use App\Form\backProdRType;
use App\Form\addbackProdRType;
use App\Repository\ProdRRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

#[Route('/prodb')]
class backProdRController extends AbstractController
{
    #[Route('/back/{idUser}', name: 'app_prod_r_back_index', methods: ['GET'])]
    public function index1(ProdRRepository $prodRRepository, UserRepository $repository, $idUser): Response
    {
        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('prod_r_back/index.html.twig', [
            'id' => $user_verif->getId(),
            'prod_rs' => $prodRRepository->findAll(),
        ]);
    }


    #[Route('/back/new/{idUser}', name: 'app_prod_r_back_new', methods:['GET', 'POST'])]
    public function new1(Request $request, EntityManagerInterface $entityManager,UserRepository $repository , $idUser): Response
    {
        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }
        $prodR = new ProdR();
        $form = $this->createForm(addbackProdRType::class, $prodR);
        $form->handleRequest($request);
        $user = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {

            $prodR->setUserId($user);

            // Persist et flush l'entité
            $entityManager->persist($prodR);
            $entityManager->flush();
           // return $this->redirectToRoute('app_prod_r_back_index', ['id' => $user_verif->getId()], [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('app_prod_r_back_index', ['idUser' => $user_verif->getId()], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('prod_r_back/new.html.twig', [
            'prod_r' => $prodR,
            // 'user' => $user,
            'form' => $form,

        ]);
    }


    #[Route('/back/{id}/{idUser}', name: 'app_prod_r_back_show', methods: ['GET'])]
    public function show1(ProdR $prodR,UserRepository $repository , $idUser): Response
    {
        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }
        return $this->render('prod_r_back/show.html.twig', [
            'prod_r' => $prodR,
        ]);
    }

    #[Route('/back/edit/{idUser}/{id}', name: 'app_prod_r_back_active', methods: ['GET', 'POST'])]
    public function edit1($idUser,$id,Request $request, ProdR $prodR, EntityManagerInterface $entityManager,UserRepository $repository,ProdRRepository $prodRRepository): Response
    {
        $user_verif = $repository->find($idUser);
        $prodR->setUserId($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }
        $check_prodR=$prodRRepository->find($id);
        if (!$id) {
            throw $this->createNotFoundException('No ID found');
        }
        $ProdRActive = $entityManager->getRepository(ProdR::class)->find($id);
        if ($ProdRActive != null) {
            $ProdRActive->setStatut(true);
            $entityManager->flush();


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
            $nomUser = $user_verif->getPrenom();
           $emailUser = $user_verif->getMail();
            // $emailUser = "aziz.msekni2@gmail.com";
            
           $mail->addAddress($emailUser);
            $mail->isHTML(true);
            $mail->Subject = 'PRODUCT VERIFIED !';
            $mail->Body = "Dear $nomUser , <br>
            We would like to inform you that your recycling product has been successfully verified. <br>

            If you did not make these changes, please contact us immediately at [Wellness.help@gmail.com] to report any suspicious activity on your account.
            
            <br> Thank you for your trust. <br>
            
            The Codologie Team";
                                $mail->AltBody = "hi";
            
                                $mail->send();
                                $this->addFlash('success', 'Congratulations');

        }
        return $this->redirectToRoute('app_prod_r_back_index', ['idUser' => $user_verif->getId()]);
    }

    #[Route('/back/{id}/{idUser}', name: 'app_prod_r_back_delete', methods: ['POST'])]
    public function delete1(Request $request, EntityManagerInterface $entityManager, UserRepository $repository , $id, $idUser): Response
    {
        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }

        $prodRRepository = $entityManager->getRepository(ProdR::class);
        $prodR = $prodRRepository->find($id);

        if (!$prodR) {
            throw $this->createNotFoundException('Product not found');
        }

        if ($this->isCsrfTokenValid('delete' . $prodR->getId(), $request->request->get('_token'))) {
            $entityManager->remove($prodR);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prod_r_back_index', ['idUser' => $user_verif->getId()], Response::HTTP_SEE_OTHER);
    }


}

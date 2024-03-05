<?php

namespace App\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Entity\ProdR;
use App\Entity\User;
// use App\Form\ProdRType;
use App\Form\backProdRType;
use App\Repository\ProdRRepository;
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
    #[Route('/back', name: 'app_prod_r_back_index', methods: ['GET'])]
    public function index1(ProdRRepository $prodRRepository): Response
    {
        return $this->render('prod_r_back/index.html.twig', [
            'prod_rs' => $prodRRepository->findAll(),
        ]);
    }

    #[Route('/back/new', name: 'app_prod_r_back_new', methods: ['GET', 'POST'])]
    public function new1(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prodR = new ProdR();
        $form = $this->createForm(backProdRType::class, $prodR);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $prodR->setUserId($user);

            // Persist et flush l'entité
            $entityManager->persist($prodR);
            $entityManager->flush();

            return $this->redirectToRoute('app_prod_r_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prod_r_back/new.html.twig', [
            'prod_r' => $prodR,
            // 'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/back/{id}', name: 'app_prod_r_back_show', methods: ['GET'])]
    public function show1(ProdR $prodR): Response
    {
        return $this->render('prod_r_back/show.html.twig', [
            'prod_r' => $prodR,
        ]);
    }

    #[Route('/back/{id}/edit', name: 'app_prod_r_back_edit', methods: ['GET', 'POST'])]
    public function edit1(Request $request, ProdR $prodR, EntityManagerInterface $entityManager): Response
    {
        // $prodR = new ProdR();

        $form = $this->createForm(backProdRType::class, $prodR);
        $form->handleRequest($request);

        // $id = $prodR->getUserId();
        // $User->set($nbUp);
        // $nbUp = $User->getNbPoints() + 1;

        // $prodR->setUserId($User->getId());

        if ($form->isSubmitted() && $form->isValid()) {
            // $User = $this->getUser();
            $userId = $prodR->getUserId();

            $User = $entityManager->getRepository(User::class)->find($userId);
            if ($User instanceof User) {

                if ($prodR->isStatut() == true) {


                    $nbUp = $User->getNbPoints() + 1;

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
                    $mail->Subject = 'PRODUCT VERIFIED !';
                    $mail->Body = "Dear $nomUser , <br>

We would like to inform you that your recycling product has been successfully verified. <br>

If you did not make these changes, please contact us immediately at [Wellness.help@gmail.com] to report any suspicious activity on your account.

<br> Thank you for your trust. <br>

The Codologie Team";
                    $mail->AltBody = "hi";

                    $mail->send();
                } else {

                    $nbUp = $User->getNbPoints() - 1;
                }
                $User->setNbPoints($nbUp);
                $entityManager->persist($User);
                $entityManager->flush();
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_prod_r_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prod_r_back/edit.html.twig', [
            'prod_r' => $prodR,
            'form' => $form,
        ]);
    }

    #[Route('/back/{id}', name: 'app_prod_r_back_delete', methods: ['POST'])]
    public function delete1(Request $request, ProdR $prodR, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $prodR->getId(), $request->request->get('_token'))) {
            $entityManager->remove($prodR);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prod_r_back_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class LoginUser extends AbstractController
{
    private $passwordHasher;
    private $session;

    public function __construct(UserPasswordHasherInterface $passwordHasher, SessionInterface $session)
    {
        $this->passwordHasher = $passwordHasher;
        $this->session = $session;
    }

    #[Route('/user/login', name: 'login_user')]
    public function login_user(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserLoginType::class);
        $error = "";
        $e = "";
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $entityManager->getRepository(User::class)->findOneBy(['mail' => $data['mail']]);
            if ($user) {
                /*
                if ($data['password']) {
                    if ($this->passwordHasher->isPasswordValid($user, $data['password'])) {

                        $error = "yes";
                */
                if($user->getPassword() == $data['password']) {
                    $this->session->start();
                    $this->session->set('user_id', $user->getId());
                    $this->session->set('user_email', $user->getMail());
                    return $this->redirectToRoute('home');
                }


            }
        }

        return $this->render('LoginUser.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
            'e' => $e,
        ]);
    }

    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        return $this->render('home.html.twig', [
            'user_email' => $this->session->get('user_email')
        ]);
    }
    #[Route('/home1', name: 'home1')]
    public function home1(): Response
    {
        return $this->render('RegisterUser.html.twig', [
        ]);
    }

}
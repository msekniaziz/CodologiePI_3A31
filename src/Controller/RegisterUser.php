<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserAddType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\MigratingPasswordHasher ;
class RegisterUser extends AbstractController
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    #[Route('/user/new', name: 'new_user')]
    public function addUser(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserAddType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $newUser = new User();
            $newUser->setNom($user->getNom());
            $newUser->setPrenom($user->getPrenom());
            $newUser->setMail($user->getMail());
            $newUser->setTel($user->getTel());
            $newUser->setGender($user->getGender());
            $newUser->setAge($user->getAge());
            $newUser->setDateBirthday($user->getDateBirthday());
            $password = $user->getPassword();
            $confirmPassword = $user->getConfirmpassword();
            if ($password === $confirmPassword) {
                $hashedPassword1 = $this->hasher->hashPassword(
                    $newUser,
                    $password
                );
                $hashedPassword2 = $this->hasher->hashPassword(
                    $newUser,
                    $confirmPassword
                );
                $newUser->setPassword($hashedPassword1);
                $newUser->setConfirmpassword($hashedPassword2);
                $entityManager->persist($newUser);
                $entityManager->flush();
                return $this->redirectToRoute('new_user');
            }
        }
        return $this->render('RegisterUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
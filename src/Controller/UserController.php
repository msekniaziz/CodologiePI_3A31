<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserAddType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class UserController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    // add user signup
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
                $this->addFlash('success','Account created ! Check Your Mail For Verification');
                return $this->redirectToRoute('new_user');
            }
        }
        return $this->render('RegisterUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // route to user settings(back) display function back
    #[Route('/user_display', name: 'user_display')]
    public function user_back(): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();
        return $this->render('user_display_back.html.twig', [
            'user' => $users,
        ]);
    }

    // route to back
    #[Route('/back', name: 'back')]
    public function back(): Response
    {
        return $this->render('back.html.twig');
    }

    //delete user
    #[Route('user/delete/{id}', name: 'user_delete')]
    public function user_delete($id, EntityManagerInterface $entityManager): Response
    {
        if (!$id) {
            throw $this->createNotFoundException('No ID found');
        }
        $user = $entityManager->getRepository(User::class)->find($id);
        if ($user != null) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('user_display');
    }

    //modify user
    #[Route('user/modify/{id}', name: 'user_modify')]
    public function user_modify(UserRepository $repository, $id, Request $request, ManagerRegistry $registry): Response
    {
        $user = $this->getUser();
        $author = $repository->find($id);
        $form = $this->createForm(EditAuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $registry->getManager();
            $author = $form->getData();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute("app_author_get");
        }
        return $this->render('account_settings.html.twig', ['f' => $form->createView(),]);
    }

    // forgot password function + mail verification
    #[Route('/user/change_password', name: 'forgot_password_user')]
    public function user_forgot_password(Request $request, ManagerRegistry $registry): Response
    {
        $user = new User();
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $newUser = new User();
            $mail=$user->getMail();
            $author = $registry->getRepository(User::class)->find($mail);
            if($author)
            {

            }
            else{

            }

        }
        return $this->render('RegisterUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
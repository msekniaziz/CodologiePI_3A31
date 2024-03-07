<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Form\ConfirmCodeType;
use App\Form\ResetPasswordType;
use App\Form\UserModifyType;
use Doctrine\ORM\EntityManagerInterface;
use Twilio\Rest\Client;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserAddType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Knp\Component\Pager\PaginatorInterface;
class UserController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;


    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;

    }

    // add user signup
    #[Route('/user/new', name: 'new_user')]
    public function addUser(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
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
                $session->set('isNewUserAdded', true);
                $this->addFlash('success', 'Congratulations ! Welcome to our Website');
                return $this->redirectToRoute('new_user');
            }
        }
        return $this->render('RegisterUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // route to user settings(back) display function back

    #[Route('/user/{id}', name: 'user_display_back')]
    public function user_back(Request $request, PaginatorInterface $paginator, FlashyNotifier $flashy, SessionInterface $session): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $query = $userRepository->findAllWithRoleUserQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );
        $totalPages = ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage());
        $isNewUserAdded = $session->get('isNewUserAdded', false);
        if ($isNewUserAdded) {
            $notificationLink = 'a new user is added';
            $flashy->success('A new user is added', $notificationLink);
            $session->set('isNewUserAdded', false);
        }
        return $this->render('user_display_back.html.twig', [
            'users' => $pagination,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/user_display_front/{id}', name: 'user_show_details')]
    public function show_details_modify(UserRepository $repository, $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $repository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $form = $this->createForm(UserModifyType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'User ' . $user->getNom() . ' ' . $user->getPrenom() . ' updated successfully');
            return $this->redirectToRoute('user_show_details', ['id' => $user->getId()]);
        }
        return $this->render('user_display_front.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    //delete user
    #[Route('/{id}/delete/{idDelete}', name: 'user_delete')]
    public function user_delete(UserRepository $repository, $id, EntityManagerInterface $entityManager, $idDelete): Response
    {
        $user = $repository->find($id);
        $userDelete = $repository->find($idDelete);
        if (!$idDelete) {
            throw $this->createNotFoundException('No ID found');
        }
        $userDelete = $entityManager->getRepository(User::class)->find($idDelete);
        if ($userDelete != null) {
            $entityManager->remove($userDelete);
            $entityManager->flush();
        }
        return $this->redirectToRoute('user_display_back', ['id' => $user->getId()]);
    }

    // active user account by admin in the backend
    #[Route('/{id}/active/{idActive}', name: 'user_active')]
    public function user_active(UserRepository $repository, $id, EntityManagerInterface $entityManager, $idActive): Response
    {
        $user = $repository->find($id);
        $userActive = $repository->find($idActive);
        if (!$idActive) {
            throw $this->createNotFoundException('No ID found');
        }
        $userActive = $entityManager->getRepository(User::class)->find($idActive);
        if ($userActive != null) {
            $userActive->setStatus("active");
            $entityManager->flush();
        }
        return $this->redirectToRoute('user_display_back', ['id' => $user->getId()]);
    }

    // inactive account back
    #[Route('/{id}/inactive/{idInActive}', name: 'user_inactive')]
    public function user_inactive(UserRepository $repository, $id, EntityManagerInterface $entityManager, $idInActive): Response
    {
        $user = $repository->find($id);
        $userInActive = $repository->find($idInActive);
        if (!$idInActive) {
            throw $this->createNotFoundException('No ID found');
        }
        $userInActive = $entityManager->getRepository(User::class)->find($idInActive);
        if ($userInActive != null) {
            $userInActive->setStatus("inactive");
            $entityManager->flush();
        }
        return $this->redirectToRoute('user_display_back', ['id' => $user->getId()]);
    }


    //forgot password user on login page
    #[Route('/password', name: 'user_password_forgot')]
    public function forgotPassword(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tel = $form->get('tel')->getData();
            $user = $userRepository->findOneBy(['tel' => $tel]);
            if ($user) {
                $resetCode = sprintf('%04d', mt_rand(0, 9999));
                // Twilio configuration
                $sid = "ACc70851690f1f6d495e9c15e22dc846dd";
                $token = "9ce0572c7b73504697a631aa3ece81d2";
                $twilio = new Client($sid, $token);
                $message = $twilio->messages
                    ->create(
                        "+21696353942",
                        [
                            "from" => "+17815605297",
                            "body" => "Hi " . " " . $user->getNom() ." " .  $user->getPrenom() . " ". "This is your code to reset password : " . $resetCode
                        ]
                    );

                return $this->redirectToRoute('password_code_confirm', ['idC' => $user->getId(), 'code' => $resetCode]);
            } else {
                $this->addFlash('error', 'Phone number does not exist');
                return $this->redirectToRoute('user_password_forgot');
            }
        }
        return $this->render('user_forgot_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idC}/{code}', name: 'password_code_confirm')]
    public function password_code(UserRepository $repository , $idC , $code,Request $request): Response
    {

        $newUser = $repository->find($idC);
        $form = $this->createForm(ConfirmCodeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $code1 = $user['code'];
            if($code1 == $code)
            {
                return $this->redirectToRoute('user_change_password', ['idC' => $newUser->getId()]);
            }
            else{
                $this->addFlash('error', 'Invalid code');
            }
        }
        return $this->render('code_confirm_check.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{idC}', name: 'user_change_password')]
    public function change_password(Request $request, UserRepository $repository, $idC, EntityManagerInterface $entityManager): Response
    {
        $newUser = $repository->find($idC);
        $form = $this->createForm(ChangePasswordType::class,$newUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
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
            }
                $newUser->setPassword($hashedPassword1);
                $newUser->setConfirmpassword($hashedPassword2);
                $entityManager->persist($newUser);
                $entityManager->flush();
                return $this->redirectToRoute('app_login');
        }
        return $this->render('change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
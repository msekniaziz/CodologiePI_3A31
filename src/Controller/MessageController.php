<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\DateTime;

#[Route('/message')]
class MessageController extends AbstractController
{
    private $security;
    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }
    #[Route('/{id}', name: 'app_message_index', methods: ['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->security->getUser();

        // Query the MessageRepository for messages where the receiverId matches the user's ID
        $messages = $messageRepository->findBy([
            'receiverId' => $user->getId(),
        ]);

        // Extract the IDs of the messages into a separate array
        $ids = array_map(function ($message) {
            return $message->getId();
        }, $messages);

        // Sort the IDs and re-arrange the messages array accordingly
        array_multisort($ids, SORT_ASC, $messages);

        // Pass the sorted messages array to the template
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    
    #[Route('/{id}/new', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $sender = $this->getUser();
    
        // Assuming you're getting the receiver's username directly in the request
        $receiverUsername = $request->request->get('nom');
    
        // Trim whitespace from the username
        $receiverUsername = trim($receiverUsername);
    
        // Retrieve the receiver's user entity based on the username
        $receiver = $userRepository->findOneBy(['nom' => $receiverUsername]);
    
        if (!$receiver) {
            throw $this->createNotFoundException('Receiver with username "' . $receiverUsername . '" not found.');
        }
    
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSenderId($sender);
            $message->setReceiverId($receiver);
            $message->setTimestamp(new \DateTime());
    
            $entityManager->persist($message);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_message_index', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('message/new.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, MessageRepository $messageRepository): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageRepository->save($message, true);

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, MessageRepository $messageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
            $messageRepository->remove($message, true);
        }

        return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/', name: 'app_message_recu', methods: ['GET'])]
    public function mrecu(MessageRepository $messageRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->security->getUser();

        $messages = $messageRepository->findByReceiver($user);

        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }
}
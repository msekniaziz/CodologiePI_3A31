<?php

namespace App\Controller;

use App\Entity\PtCollect;
use App\Entity\User;
use App\Form\PtCollectType;
use App\Repository\PtCollectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TypeDispoRepository;


#[Route('/pt/collect')]
class PtCollectController extends AbstractController
{
    #[Route('/{idUser}', name: 'app_pt_collect_index', methods: ['GET'])]
    public function index($idUser,UserRepository $repository,TypeDispoRepository $typeDispoRepository,PtCollectRepository $ptCollectRepository): Response
    {
        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else {
            // Gérer le cas où aucun utilisateur n'est authentifié
            // Par exemple, rediriger vers la page de connexion

            $typeDispos = $typeDispoRepository->findAll();

            return $this->render('pt_collect/test.html.twig', [
                'pt_collects' => $ptCollectRepository->findAll(),
                'typeDispos' => $typeDispos,

            ]);
        }
    }
    #[Route('/map/{idUser}', name: 'app_pt_collect_indexMap', methods: ['GET'])]
    public function indexMap(TypeDispoRepository $typeDispoRepository, UserRepository $repository , PtCollectRepository $ptCollectRepository, $idUser): Response
    {
        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }

        $typeDispos = $typeDispoRepository->findAll();

        return $this->render('pt_collect/index2.html.twig', [
            'pt_collects' => $ptCollectRepository->findAll(),
            'typeDispos' => $typeDispos,
            'idUser' => $idUser // Fournir le paramètre "idUser" à la vue


        ]);
    }

    #[Route('/new/{idUser}', name: 'app_pt_collect_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserRepository $repository , $idUser): Response
    {
        $user_verif = $repository->find($idUser);
        if (!$user_verif) {
            throw $this->createNotFoundException('User not found');
        }
        $ptCollect = new PtCollect();
        $form = $this->createForm(PtCollectType::class, $ptCollect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ptCollect);
            $entityManager->flush();

            return $this->redirectToRoute('app_pt_collect_index', ['idUser' => $user_verif->getId()], [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pt_collect/new.html.twig', [
            'pt_collect' => $ptCollect,
            'form' => $form,
        ]);
    }
}

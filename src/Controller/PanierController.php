<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Annonces;
use App\Repository\AnnoncesRepository;
use Symfony\Component\HttpFoundation\Request;

#[Route('/cart', name: 'cart_')]
class PanierController extends AbstractController
{
    #[Route('/{idUser}', name: 'index')]
    public function indexPanier(SessionInterface $session, AnnoncesRepository $annoncesRepository,$idUser,UserRepository $repository)
    {
        $user=$repository->find($idUser);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $panier = $session->get('panier', []);

        // On initialise des variables
        $data = [];
        $total = 0;

        foreach ($panier as $id => $quantity) {
            // On vérifie si l'annonce existe dans la base de données
            $annonce = $annoncesRepository->find($id);

            // Si l'annonce n'existe pas, on continue à la prochaine itération
            if (!$annonce) {
                continue;
            }

            $data[] = [
                'product' => $annonce,
                'quantity' => $quantity
            ];
           // dd($data);

            $total += $annonce->getPrix() * $quantity;
        }

        return $this->render('panier/index.html.twig', compact('data', 'total'));
    }

    #[Route('/{idUser}/{id}', name: 'add')]
    public function add_product_panier($id,Annonces $annonce , UserRepository $repository ,$idUser, SessionInterface $session , Request $request)
    {
        // On récupère l'id du produit
        $user=$repository->find($idUser);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
       // $id = $annonce->getId();
        $id = $request->attributes->get('id');
        //dd($id);
        // On récupère le panier existant
        $panier = $session->get('panier', []);

        // On ajoute le produit dans le panier s'il n'y est pas encore
        // Sinon on incrémente sa quantité
        if (empty($panier[$id])) {
            $panier[$id] = 1;

        } else {
           $panier[$id]++;
        }

        $session->set('panier', $panier);

        // Dump le contenu du panier
       // dd($session);

        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index', ['idUser' => $user->getId()]);
    }
    #[Route('/remove/{idUser}/{id}', name: 'remove')]
    public function remove(Annonces $annonce, SessionInterface $session , Request $request,UserRepository $repository,$idUser)
    {
        $user=$repository->find($idUser);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }


        //On récupère l'id du produit
       // $id = $annonce->getId();
        $id = $request->attributes->get('id');

        // On récupère le panier existant
        $panier = $session->get('panier', []);

        // On retire le produit du panier s'il n'y a qu'1 exemplaire
        // Sinon on décrémente sa quantité
        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);

        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index', ['idUser' => $user->getId()]);
    }

    #[Route('/delete/{idUser}/{id}', name: 'delete')]
    public function delete(Annonces $annonce, SessionInterface $session , Request $request,UserRepository $repository,$idUser)
    {
        $user=$repository->find($idUser);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        //On récupère l'id du produit
       // $id = $annonce->getId();
        $id = $request->attributes->get('id');

        // On récupère le panier existant
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index', ['idUser' => $user->getId()]);
    }

    #[Route('/empty/{idUser}', name: 'empty')]
    public function empty(SessionInterface $session, UserRepository $repository, $idUser)
    {

        $user = $repository->find($idUser);
       // dd($user);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $session->remove('panier');
        return $this->redirectToRoute('cart_index', ['idUser' => $idUser]); // Utilisez $idUser au lieu de $user->getId()
    }


}

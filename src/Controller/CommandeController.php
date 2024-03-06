<?php

namespace App\Controller;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Commandes;
use App\Entity\Annonces;
use App\Repository\AnnoncesRepository;
use App\Entity\User;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;





class CommandeController extends AbstractController
{
    #[Route('/commande/{idUser}/{id}', name: 'app_commande')]
    public function index($id,$idUser,UserRepository $repository): Response
    {
        $user=$repository->find($idUser);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
          }
        $entityManager = $this->getDoctrine()->getManager();
        $annonce = $entityManager->getRepository(Annonces::class)->find($id);

        return $this->render('commande/index.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    #[Route('/commande/new/{idUser}/{id}', name: 'newcommande')]
    public function add(Request $request, $idUser ,$id,UserRepository $repository, Security $security ,FlashyNotifier $flashy, SessionInterface $session) : Response
    {
        $user=$repository->find($idUser);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $entityManager = $this->getDoctrine()->getManager();

        $annonce = $entityManager->getRepository(Annonces::class)->find($id);

        if (!$annonce) {
            throw $this->createNotFoundException('Annonce non trouvée');
        }
        $user = $security->getUser();
        $commande = new Commandes();
        $commande->setIdUserC($user);
        $commande->setEtat('1');
        $dateNow = new \DateTime();
        $commande->setDate($dateNow);


        $annonce->setStatus('1');

        $entityManager->persist($commande);
        $entityManager->flush();
        $userverif=$annonce->getUser();
       // $session->set('user_id', $userverif->getId());
        $session->set('commande_success', true);
        return $this->redirectToRoute('checkout');



    }
    #[Route('/affichebackCommande/{id}', name: 'Order_aff')]
    public function afficheOrder(UserRepository $repository, $id, Request $request): Response
    {
        $user = $repository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $entityManager = $this->getDoctrine()->getRepository(Commandes::class);
        $commande = $entityManager->findAll();

        return $this->render('commande/AfficheOrder.html.twig', [
            'commande' => $commande ,
            'dateString' => $commande,
        ]);
    }
    #[Route('/deleteOrder/{id}', name: 'delete_Order')]
    public function supprimercommande($id): Response
    {
        $commande = $this->getDoctrine()->getRepository(Commandes::class)->find($id);

        if (!$commande) {
            throw $this->createNotFoundException('commande not found');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($commande);
        $entityManager->flush();
        return $this->redirectToRoute('Order_aff');


    }

    #[Route('/ajout', name: 'addOrderToCart')]
    public function addCart(SessionInterface $session, AnnoncesRepository $annoncesRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);
       // dd($panier);
        if($panier === []){
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('aff_annonces', ['id' => $this->getUser()->getId()]);

        }

        //Le panier n'est pas vide, on crée la commande
        $commande = new Commandes();

        // On remplit la commande
       $commande->setIdUserC($this->getUser());


        // On parcourt le panier pour créer les détails de commande
        foreach($panier as $item => $quantity){


            // On va chercher le produit
            $annonce = $annoncesRepository->find($item);

            $price = $annonce->getPrix();

            $commande->setEtat('1');
            $dateNow = new \DateTime();
            $commande->setDate($dateNow);


            $annonce->setStatus('1');
        }

        // On persiste et on flush
        $em->persist($commande);
        $em->flush();

        $session->remove('panier');

        $this->addFlash('message', 'Commande créée avec succès');
        return $this->redirectToRoute('checkout');
    }




}
class PdfController extends AbstractController
{
    #[Route('/generate-pdf', name: 'generate_pdf')]
    public function generatePdf(): Response
    {
        // Récupérer les données à afficher dans le PDF, par exemple à partir de la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $orders = $entityManager->getRepository(Commandes::class)->findAll();

        // Rendre le template Twig avec les données
        $html = $this->renderView('commande/pdf.html.twig', [
            'orders' => $orders,
        ]);

        // Instancier Dompdf
        $dompdf = new Dompdf();

        // Charger le HTML dans Dompdf
        $dompdf->loadHtml($html);

        // (Optionnel) Définir les options de rendu
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF
        $dompdf->render();

        // Renvoyer une réponse avec le contenu du PDF
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            array(
                'Content-Type' => 'application/pdf'
            )
        );
    }
}
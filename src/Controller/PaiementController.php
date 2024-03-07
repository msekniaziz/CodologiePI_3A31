<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Checkout\Session;
use Stripe\Stripe;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\AssociationRepository;
class PaiementController extends AbstractController
{
    #[Route('/paiement', name: 'app_payment')]
    public function index(): Response
    {
        return $this->render('paiement/index.html.twig', [
            'controller_name' => 'PaiementController',
        ]);
    }


    #[Route('/checkout', name: 'checkout')]
    public function checkout($stripeSK): Response
    {
        Stripe::setApiKey($stripeSK);  //secretkey



        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => 'Donation',
                        ],
                        'unit_amount'  => 2000*100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        // dd($session);
        return $this->redirect($session->url, 303);
    }

    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('paiement/success.html.twig', []);
    }


    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('paiement/cancel.html.twig', []);
    }


}
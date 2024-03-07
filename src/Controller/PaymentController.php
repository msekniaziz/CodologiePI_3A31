<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

// use App\Repository\DonArgentRepository;
class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    { 
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

#[Route('/checkout', name: 'checkout')]
    public function checkout($stripeSKL): Response
    {
            Stripe::setApiKey($stripeSKL);  //secretkey
        
        
        
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
                return $this->render('payment/success.html.twig', []);
    }
    
    
    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig', []);
    }
    
    
}

// #[Route('/checkout', name: 'checkout')]
// public function checkout(SessionInterface $session, $stripeSK): Response
// {
//     Stripe::setApiKey($stripeSK);

//     $donationId = $session->get('donation_id'); // Récupérer l'ID du don de la session

//     $session = Session::create([
//         'payment_method_types' => ['card'],
//         'line_items' => [
//             [
//                 'price_data' => [
//                     'currency' => 'usd',
//                     'product_data' => [
//                         'name' => 'Donation',
//                     ],
//                     'unit_amount' => 2000 * 100,
//                 ],
//                 'quantity' => 1,
//             ]
//         ],
//         'mode' => 'payment',
//         'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL) . '?donation_id=' . $donationId, // Passer l'ID du don dans l'URL de succès
//         'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
//     ]);

//     return $this->redirect($session->url, 303);
// }

// #[Route('/success-url', name: 'success_url')]
// public function successUrl(Request $request, EntityManagerInterface $entityManager): Response
// {
//     $donationId = $request->query->get('donation_id'); // Récupérer l'ID du don depuis l'URL

//     // Vérifier si le paiement a été réussi
//     if ($donationId) {
//         // Ajouter le don à la base de données
//         $donation = $entityManager->getRepository(DonArgent::class)->find($donationId);
//         if ($donation) {
//             $donation->setPaymentStatus('success');
//             $entityManager->persist($donation);
//             $entityManager->flush();
//         }
//     }

//     return $this->render('payment/success.html.twig', []);
// }
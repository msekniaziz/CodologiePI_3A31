<?php

namespace App\Controller;
use App\Entity\ProduitTrocWith;
use App\Form\SearchFormType;

use App\Entity\ProduitTroc;
use App\Form\ProduitTroc1Type;
use App\Repository\ProduitTrocRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Exception\FileException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



#[Route('/produit/troc')]
class ProduitTrocController extends AbstractController
{
    private const API_URL = 'https://api.openai.com/v1/engines/text-davinci-003/completions';
    private const API_KEY = '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';
    #[Route('/', name: 'app_produit_troc_index', methods: ['GET'])]
    public function index(ProduitTrocRepository $produitTrocRepository): Response
    {
        return $this->render('index1.html.twig', [
            'produit_trocs' => $produitTrocRepository->findAll(),
        ]);
    }
    #[Route('/m', name: 'app_produit_troc_mine', methods: ['GET'])]
    public function indexmi(ProduitTrocRepository $produitTrocRepository): Response
    { $user = $this->getUser();


        // Vérifier si l'utilisateur est authentifié
        if ($user) {
            // Récupérer les dons de l'utilisateur authentifié
            $don_bien_materiels = $produitTrocRepository->findBy(['id_user' => $user]);
        } else {
            // Gérer le cas où aucun utilisateur n'est authentifié
            // Par exemple, rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('index1.html.twig', [
            'produit_trocs' => $don_bien_materiels,
        ]);
        
    }
    #[Route('/search', name: 'app_produit_troc_search', methods: ['POST'])]

// Inside your controller action method
public function search(Request $request, ProduitTrocRepository $produitTrocRepository): Response
{
    $form = $this->createForm(SearchFormType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Get the search criteria from the form
        $nom = $form->get('nom')->getData();
        $prenom = $form->get('nom_produit_recherche')->getData();

        // Perform the search using your repository's custom method
        $results = $produitTrocRepository->findByNomAndPrenom($nom, $prenom);
        // Replace 'findByNomAndPrenom' with the actual method in your repository

        // Pass the results to your template
        return $this->render('index1.html.twig', [
            'results' => $results,
        ]);
    }

    return $this->render('index1.html.twig', [
        'form' => $form->createView(),
    ]);
}



#[Route('/chat', name: 'app_chat_index')]
public function index11(Request $request): Response
{
    $question = $request->query->get('question');

    if ($question) {
        try {
            // Get the answer from the API
            $answer = $this->getAnswerFromAPI($question);
            return $this->render('chatgbt.html.twig', [
                'answer' => $answer,
            ]);
        } catch (\Exception $e) {
            // Handle API request errors
            return $this->render('chatgbt.html.twig', [
                'answer' => 'I\'m sorry, I can only answer questions related to JardinDars products and services.',
            ]);
        }
    }

    // No question provided, render the chat page with default message
    return $this->render('chatgbt.html.twig', [
        'answer' => 'I\'m sorry, I can only answer questions related to JardinDars products and services.',
    ]);
}

    #[Route('/b', name: 'app_produit_troc_index_back', methods: ['GET'])]
    public function indexback(ProduitTrocRepository $produitTrocRepository): Response
    {
        return $this->render('prod_troc.html.twig', [
            'produit_trocs' => $produitTrocRepository->findAll(),
        ]);
    }
    #[Route('/l', name: 'app_produit_troc_indexES', methods: ['GET'])]
    public function indexEd(ProduitTrocRepository $produitTrocRepository): Response
    {
        return $this->render('indexES.html.twig', [
            'produit_trocs' => $produitTrocRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_troc_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();
    $produitTroc = new ProduitTroc();
    $form = $this->createForm(ProduitTroc1Type::class, $produitTroc);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('images_directory'), // Destination directory, should be defined in the configuration file
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle the exception, such as logging an error message or displaying a user-friendly error
                echo 'An error occurred: ' . $e->getMessage();
                // You might want to return an error response here
            }

            $produitTroc->setImage($newFilename);
            $produitTroc->setStatut(0); // Use setStatut instead of getStatut
            $produitTroc->setIdUser($user);
        }var_dump($imageFile);

        $entityManager->persist($produitTroc);
        $entityManager->flush();

        return $this->redirectToRoute('app_produit_troc_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('produit_troc/new1.html.twig', [
        'produit_troc' => $produitTroc,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_produit_troc_show', methods: ['GET'])]
    public function show(ProduitTroc $produitTroc): Response
    {
        return $this->render('produit_troc/show.html.twig', [
            'produit_troc' => $produitTroc,
        ]);
    }

    
    #[Route('/{id}/edit', name: 'app_produit_troc_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProduitTroc $produitTroc, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitTroc1Type::class, $produitTroc);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('image')->getData();
    
            // Debugging: Dump the image file data
            dump($imageFile);
    
            // Check if a new image was uploaded
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // You may want to log the error or display a user-friendly message
                    // and return a response indicating failure
                    // For example:
                    $this->addFlash('error', 'Failed to upload image.');
                    return $this->redirectToRoute('app_produit_troc_edit', ['id' => $produitTroc->getId()]);
                }
    
                // Remove the old image file if it exists
                $oldFilename = $produitTroc->getImage();
                if ($oldFilename) {
                    unlink($this->getParameter('images_directory').'/'.$oldFilename);
                }
    
                // Set the new image filename
                $produitTroc->setImage($newFilename);
            }
    
            // Persist changes to the entity
            $entityManager->flush();
    
            // Redirect to the index page after successful edit
            return $this->redirectToRoute('app_produit_troc_index');
        }
    
        // Render the edit form
        return $this->renderForm('produit_troc/edit1.html.twig', [
            'produit_troc' => $produitTroc,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_produit_troc_delete', methods: ['POST'])]
    public function delete(Request $request, ProduitTroc $produitTroc, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produitTroc->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produitTroc);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_troc_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/1/{id}', name: 'app_produit_troc_deleteback', methods: ['POST'])]
    public function deleteback(Request $request, ProduitTroc $produitTroc, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produitTroc->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produitTroc);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_troc_index_back', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('produit_troc_with/{id}', name: 'app_produit_ajoutpttocw', methods: ['POST'])]
    public function affichajout(Request $request, ProduitTrocWith $produitTroc, EntityManagerInterface $entityManager): Response
    {
        return $this->render('produit_troc_with/new.html.twig', [
            'produit_troc_with' => $produitTroc,
        ]); 

    }
    private function getAnswerFromAPI(string $question): string
{
    // Your predefined answers
    $echange = 'Our site helps you exchange your products easily and quickly with the security of your home. You can easily add a product to get rid of.';
    $amenaAnswer = 'JardinDars is a website to help you buy, sell, and exchange products easily and safely.';
    $termsAndConditions =
         'En utilisant l\'application JardinDars, vous acceptez les présentes conditions d\'utilisation. Veuillez les lire attentivement avant d\'utiliser l\'application.
        
        Utilisation de l\'application
        JardinDars est une application de logistique qui fournit des services de livraison et de location de véhicules. L\'utilisation de l\'application est réservée aux personnes âgées de 18 ans ou plus.
    
        Inscription et compte utilisateur
        Pour utiliser certains services de l\'application, vous devez créer un compte utilisateur en fournissant des informations personnelles précises et à jour. Vous êtes entièrement responsable de la protection et de la confidentialité de votre compte utilisateur. Vous ne devez pas partager votre compte avec d\'autres personnes et vous êtes entièrement responsable de toutes les activités effectuées sous votre compte.
    
        Conditions de paiement
        L\'utilisation de certains services de l\'application peut entraîner des frais. Vous êtes entièrement responsable du paiement de tous les frais liés à l\'utilisation de l\'application. Les modes de paiement acceptés sont ceux spécifiés dans l\'application.
    
        Propriété intellectuelle
        Tous les droits de propriété intellectuelle associés à l\'application et à son contenu, y compris mais sans s\'y limiter, les marques, les logos, les textes, les images, les graphiques, les sons et les vidéos, sont la propriété d\'Amena ou de ses fournisseurs de contenu. Vous ne devez pas copier, reproduire, distribuer, transmettre, afficher, vendre, concéder sous licence ou exploiter de toute autre manière tout contenu de l\'application sans l\'autorisation écrite préalable d\'Amena.
    
        Limitation de responsabilité
        JardinDars ne peut garantir la qualité, la fiabilité, l\'exactitude ou l\'exhaustivité de tout contenu de l\'application. L\'utilisation de l\'application est à vos risques et périls. Amena ne peut être tenu responsable de tout dommage résultant de l\'utilisation ou de l\'incapacité à utiliser l\'application, y compris les dommages directs, indirects, accessoires, spéciaux ou consécutifs.

        Modification des conditions d\'utilisation
        JardinDars se réserve le droit de modifier les présentes conditions d\'utilisation à tout moment sans préavis. Il est de votre responsabilité de vérifier régulièrement les conditions d\'utilisation pour être informé des modifications éventuelles.
        
        En utilisant l\'application JardinDars, vous acceptez les présentes conditions d\'utilisation. Si vous ne les acceptez pas, veuillez ne pas utiliser l\'application.
        Limitation de responsabilité
        JardinDars ne sera pas responsable des dommages directs, indirects, spéciaux, consécutifs ou accessoires découlant de l\'utilisation ou de l\'impossibilité d\'utiliser la plateforme ou de son contenu.
        
        Droit applicable
        Ces conditions sont régies par les lois en vigueur en Tunisie. Tout litige relatif à ces conditions sera soumis aux tribunaux compétents de France.
        
        Contact
        Si vous avez des questions concernant ces conditions, vous pouvez contacter Amena à l\'adresse suivante : contact@amena.com.';
        
    if (preg_match('/conditions|termes|utilisation|propriété/i', $question)) {
        return $termsAndConditions;
    } elseif (preg_match('/Jardindart/i', $question)) {
        return $amenaAnswer;
    } elseif (preg_match('/troc|echange|house|garden|exchange/i', $question)) {
        return $echange;
    } else {
        // If the question doesn't match any predefined pattern, query the API
        $postData = [
            'prompt' => $question,
            'temperature' => 0.7,
            'max_tokens' => 4000,
            'top_p' => 1,
            'frequency_penalty' => 0.5,
            'presence_penalty' => 0,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => self::API_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . self::API_KEY,
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $responseArray = json_decode($response, true);
        $answer = $responseArray['choices'][0]['text'];

        // Use regex to extract email address from the answer if present
        $regex = '/(?<=contact@amena.com).*$/im';
        preg_match($regex, $answer, $matches);
        if (!empty($matches)) {
            return trim($matches[0]);
        }
        return $answer;
    }
}
}
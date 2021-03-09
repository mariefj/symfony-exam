<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContactRepository;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ContactRepository $repo): Response
    {
        // Récupère tous les contacts de la BDD
        $contacts = $repo->findAll();
        
        /* Si on veut rechercher par format d'email :
        $contacts = $repo->findByEmail("test@test.com"); */

        return $this->render('default/index.html.twig', [
            'contacts' => $contacts
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'un nouveau contact à chaque actualisation de la page
        $contact = new Contact();
    
        // Affectation des valeurs demandées
        // $contact->setEmail("test@test.com")
        //         ->setSubject("Ceci est un test")
        //         ->setMessage("Un message de test, pouvant être long, ou non. Celui-ci ne l'est pas :)");

        $form =$this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder l'entité contact
            $entityManager->persist($contact);
            // Mettre cette entité dans la BDD
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

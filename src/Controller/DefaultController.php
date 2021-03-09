<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContactRepository;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ContactRepository $repo): Response
    {
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
    public function contact(EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
    
        $contact->setEmail("test@test.com")
                ->setSubject("Ceci est un test")
                ->setMessage("Un message de test, pouvant être long, ou non. Celui-ci ne l'est pas :)");

        $entityManager->persist($contact);
        $entityManager->flush();

        return $this->render('default/contact.html.twig');
    }
}

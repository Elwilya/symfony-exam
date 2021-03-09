<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    /*
        Récupération des contacts via les paramètres passés en fonction ContactRepository.
    */

    public function index(ContactRepository $repository): Response
    { /*
        Déclaration de la variable Contacts dans laquelle on récupère les infos.
        */
        $contacts = $repository->findAll();

        /*
        Retourne un tableau qui affiche les données contenues dans $contacts.
        */
        return $this->render('default/index.html.twig', [
            'contacts' => $contacts
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(EntityManagerInterface $entityManager, Request $request) : Response
    {
        $contact = new Contact();

        $contact->setEmail('test@test.com');
        $contact->setSubject('Ceci est un test');
        $contact->setMessage('Un message de test, pouvant être long, ou non. Celui-ci ne l\'est pas :) .');

        $form =$this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder l'entité contact
            $entityManager->persist($contact);
            // Mettre cette entité dans la BDD
            $entityManager->flush();

            return $this->redirectToRoute('default');
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

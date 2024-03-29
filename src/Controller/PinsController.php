<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Entity\User;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    
    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function index(PinRepository $pinRepository, EntityManagerInterface $em): Response
    {
        // Ajouter un pin à un utilisateur
        // $p1 = $pinRepository->findOneBy(array('id' => 1));
        // $u1 = $userRepository->findOneBy(array('id' => 1));
        // $u1->addPin($p1);
        // $em->persist($u1);
        // $em->flush();

        /* Modifier le pin*/
        // $p6 = $pinRepository->findOneBy(array('id' => 5));
        // $p6->setTitle('Pin 5');
        // $p6->setDescription('Description 5');
        // $em->flush();

        /* Ordonner les pins du plus nouveau au pls ancien */
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);

        // Si on veut 2 element
        // $pins = $pinRepository->findBy([], ['createdAt' => 'DESC'], 2);

        // $pins = $pinRepository->findAll();

        return $this->render('pins/list_pins.html.twig', compact('pins'));
        // compact('pins') => ['pins' => $pins]
    }

    /* 
        @Security("is_granted('ROLE_USER') && user.getIsVerified()") => Verifie si l'utilisateur est connecté et verifie si son compte est verifié
    */
    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_USER') && user.getIsVerified()", message="You need to have a verified account")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        // if (!$this->getUser()) {
        //     throw $this->createAccessDeniedException();
        // }

        // if (! $this->getUser()->getIsVerified()) {

        //     // $this->addFlash('error', 'You need to have a verified account');
        //     // return $this->redirectToRoute('app_home');

        //     throw $this->createAccessDeniedException('You need to have a verified account');
        // }

        $pin = new Pin;
        
        // $form = $this->createFormBuilder(['title' => 'toto', 'description' => 'decription ...']) => prerempli le formulaire 
        // $form = $this->createFormBuilder($pin)
        //     ->add('title', TextType::class)
        //     ->add('description', TextareaType::class)
        //     ->getForm()
        // ;
        // getForm => permet de récuperer le formulaire
        
        $form = $this->createForm(PinType::class, $pin);

        // permet de recuperer les données du formulaire via la request
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // $form->getData() => retounre un tableau contenant les données du form
            $pin->setUser($this->getUser());
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success', 'Pin successfully created!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods={"GET"})
     */
    public function show(Pin $pin): Response
    {        
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET", "PUT"})
     * @Security("is_granted('ROLE_USER') && user.getIsVerified() && pin.getUser() == user", message="You need to have a verified account"
     */
    public function edit(Pin $pin, Request $request, EntityManagerInterface $em): Response
    {   
        $form = $this->createForm(PinType::class, $pin, [
            'method' => 'PUT',
        ]);
        
        // J'ai trouvé une réponse dans les messages de la vidéo et pour moi ça a marché aussi : Dans packages>framework.yaml , mettre a true http_method_override

        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $this->addFlash('success', 'Pin successfully edited!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/edit.html.twig', [
            'idPin' => $pin,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER') && user.getIsVerified() && pin.getUser() == user", message="You need to have a verified account")
     */
    public function delete(Request $request, Pin $pin, EntityManagerInterface $em): Response
    {
        
        $csrf = $request->request->get('csrf_token');
        if ($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(), $csrf)) {
            
            $em->remove($pin);
            $em->flush();

            $this->addFlash('info', 'Pin successfully deleted!');
        }

        return $this->redirectToRoute('app_home');
    }

    // Convention des routes:
    //     -> GET /
    //     -> GET /pins/{id}
    //     -> GET|POST /pins/create
    //     -> GET|PUT /pins/{id}/edit
    //     -> DELETE /pins/{id}
}

<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function index(PinRepository $pinRepository, EntityManagerInterface $em): Response
    {
        /* Modifier le pin*/
        $p6 = $pinRepository->findOneBy(array('id' => 5));
        $p6->setTitle('Pin 5');
        $p6->setDescription('Description 5');
        $em->flush();

        /* Ordonner les pins du plus nouveau au pls ancien */
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);

        // Si on veut 2 element
        // $pins = $pinRepository->findBy([], ['createdAt' => 'DESC'], 2);

        // $pins = $pinRepository->findAll();

        return $this->render('pins/list_pins.html.twig', compact('pins'));
        // compact('pins') => ['pins' => $pins]
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $pin = new Pin;
        // $form = $this->createFormBuilder(['title' => 'toto', 'description' => 'decription ...']) => prerempli le formulaire 
        $form = $this->createFormBuilder($pin)
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->getForm()
        ;
        // getForm => permet de récuperer le formulaire

        // permet de recuperer les données du formulaire via la request
        ($form->handleRequest($request));

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // $form->getData() => retounre un tableau contenant les données du form

            $em->persist($pin);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig', [
            'formCreate' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods={"GET"})
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }
}

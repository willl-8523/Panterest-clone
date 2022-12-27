<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PinRepository $pinRepository, EntityManagerInterface $em): Response
    {
        /* Modifier le pin*/
        $p4 = $pinRepository->findOneBy(array('id' => 4));
        $p4->setDescription('Description 4 (edit)...');
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
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show")
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }
}

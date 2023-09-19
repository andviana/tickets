<?php

namespace App\Controller;

use App\Repository\EventoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InicioController extends AbstractController
{
    #[Route('/inicio', name: 'app_inicio')]
    public function index(EventoRepository $eventoRepository): Response
    {
        $eventos = $eventoRepository->findAll();

        return $this->render('inicio/index.html.twig', [
            'eventos' => $eventos,
            'controller_name' => 'InicioController',
        ]);
    }
}

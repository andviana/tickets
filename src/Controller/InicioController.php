<?php

namespace App\Controller;

use App\Repository\EventoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class InicioController extends AbstractController
{

    #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'app_inicio')]
    public function index(EventoRepository $eventoRepository): Response
    {
        $nome = $this->getUser()->getPessoa()->getNome();
        return $this->render('inicio/index.html.twig', [
            'nome'=>$nome,
        ]);
    }
}

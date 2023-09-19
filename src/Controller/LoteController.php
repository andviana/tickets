<?php

namespace App\Controller;

use App\Entity\Evento;
use App\Entity\Lote;
use App\Entity\Ticket;
use App\Form\LoteType;
use App\Repository\EventoRepository;
use App\Repository\LoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lote')]
class LoteController extends AbstractController
{
    #[Route('/', name: 'app_lote_index', methods: ['GET'])]
    public function index(LoteRepository $loteRepository): Response
    {
        return $this->render('lote/index.html.twig', [
            'lotes' => $loteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lote_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EventoRepository $eventoRepository): Response
    {
        $lote = new Lote();
        $lote->setDataEmissao(new \DateTime());
        $form = $this->createForm(LoteType::class, $lote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evento = $eventoRepository->find('1ee5501d-c7ae-652c-aa83-7d09c4bf49a2');
            for ($i=1; $i<=$lote->getQuantidade();$i++){
                $ticket = new Ticket();
                $ticket
                    ->setUserEmissao($this->getUser())
                    ->setDataEmissao($lote->getDataEmissao())
                    ->setProduto($lote->getProduto())
                    ->setEvento($evento)
                    ->setRecolhido(false)
                    ->setLote($lote)
                    ->setNumero($i)
                    ;
                $lote->addTicket($ticket);
            }

            $entityManager->persist($lote);

//            dump($lote);die;
            $entityManager->flush();

            return $this->redirectToRoute('app_lote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lote/new.html.twig', [
            'lote' => $lote,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lote_show', methods: ['GET'])]
    public function show(Lote $lote): Response
    {
        return $this->render('lote/show.html.twig', [
            'lote' => $lote,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lote_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lote $lote, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LoteType::class, $lote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lote/edit.html.twig', [
            'lote' => $lote,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lote_delete', methods: ['POST'])]
    public function delete(Request $request, Lote $lote, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lote->getId(), $request->request->get('_token'))) {
            $entityManager->remove($lote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lote_index', [], Response::HTTP_SEE_OTHER);
    }
}

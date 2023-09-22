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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lote')]
class LoteController extends AbstractController
{
    #[Route('/', name: 'app_lote_index', methods: ['GET'])]
    public function index(LoteRepository $loteRepository, EventoRepository $eventoRepository, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $evento = $eventoRepository->find($session->get('idEvento'));
        if ($evento === null) {
            $this->addFlash('warning', 'Nenhum evento selecionado, ou evento selecionado é inválido!');
            $this->redirectToRoute('app_evento_index',[], Response::HTTP_SEE_OTHER);
        }
        $lotes = $loteRepository->findBy(['tickets.evento'=>$evento]);
        return $this->render('lote/index.html.twig', [
            'lotes' => $lotes,
        ]);
    }

    #[Route('/new', name: 'app_lote_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EventoRepository $eventoRepository, RequestStack $requestStack): Response
    {

        $lote = new Lote();
        $lote->setDataEmissao(new \DateTime());
        $form = $this->createForm(LoteType::class, $lote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $requestStack->getSession();
            $evento = $eventoRepository->find($session->get('idEvento'));
            if ($evento === null) {
                $this->addFlash('warning', 'Nenhum evento selecionado, ou evento selecionado é inválido!');
                $this->redirectToRoute('app_evento_index', [], Response::HTTP_SEE_OTHER);
            }

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

    #[Route('/{id}/lista', name: 'app_lote_lista', methods: ['GET'])]
    public function lista(Evento $evento, RequestStack $requestStack ): Response
    {
        $session = $requestStack->getSession();
        $session->set('idEvento',$evento->getId());
        return $this->redirectToRoute('app_lote_index', [], Response::HTTP_SEE_OTHER);
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


}

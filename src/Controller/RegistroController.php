<?php

namespace App\Controller;

use App\Entity\Registro;
use App\Entity\Ticket;
use App\Form\RegistroType;
use App\Repository\RegistroRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/registro')]
class RegistroController extends AbstractController
{
    #[Route('/', name: 'app_registro_index', methods: ['GET'])]
    public function index(RegistroRepository $registroRepository): Response
    {
        return $this->render('registro/index.html.twig', [
            'registros' => $registroRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_registro_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $registro = new Registro();
        $registro->setUserRegistro($this->getUser())
            ->setDataRegistro(new \DateTime());

        $form = $this->createForm(RegistroType::class, $registro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registro->getTicket()->setRegistro($registro)
                ->setRecolhido(true);
            $entityManager->persist($registro);
            $entityManager->flush();

            return $this->redirectToRoute('app_registro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registro/new.html.twig', [
            'registro' => $registro,
            'form' => $form,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/ticket/{id}', name: 'app_registro_qrcode', methods: ['GET', 'POST'])]
    public function ticketQrcode(Ticket $ticket, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($ticket->isRecolhido()) {
            $this->addFlash(
                'danger',
                sprintf('ERRO! Ticket n.º %d/%d inválido', $ticket->getNumero(), $ticket->getLote()->getId())
            );
            return $this->redirectToRoute('app_registro_index', [], Response::HTTP_SEE_OTHER);
        }
        $registro = new Registro();
        $registro->setUserRegistro($this->getUser())
            ->setDataRegistro(new \DateTime())
            ->setTicket($ticket);
        $ticket->setRegistro($registro)
            ->setRecolhido(true);

        $entityManager->persist($registro);
        $entityManager->flush();
        $this->addFlash(
            'success',
            sprintf('SUCESSO! ticket %d/%d - %s registrado com sucesso por %s',
                $ticket->getNumero(),
                $ticket->getLote()->getId(),
                strtoupper($ticket->getProduto()),
                strtoupper($registro->getUserRegistro()->getUsername())
            )
        );

        return $this->redirectToRoute('app_registro_show', ['id'=> $registro->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_registro_show', methods: ['GET'])]
    public function show(Registro $registro): Response
    {
        return $this->render('registro/show.html.twig', [
            'registro' => $registro,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_registro_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Registro $registro, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistroType::class, $registro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_registro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registro/edit.html.twig', [
            'registro' => $registro,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_registro_delete', methods: ['POST'])]
    public function delete(Request $request, Registro $registro, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $registro->getId(), $request->request->get('_token'))) {
            $entityManager->remove($registro);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_registro_index', [], Response::HTTP_SEE_OTHER);
    }
}

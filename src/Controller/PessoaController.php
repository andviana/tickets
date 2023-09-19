<?php

namespace App\Controller;

use App\Entity\Pessoa;
use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\PessoaType;
use App\Form\RegistrationFormType;
use App\Repository\PessoaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pessoa')]
class PessoaController extends AbstractController
{
    #[Route('/', name: 'app_pessoa_index', methods: ['GET'])]
    public function index(PessoaRepository $pessoaRepository): Response
    {
        return $this->render('pessoa/index.html.twig', [
            'pessoas' => $pessoaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pessoa_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pessoa = new Pessoa();
        $form = $this->createForm(PessoaType::class, $pessoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pessoa);
            $entityManager->flush();

            return $this->redirectToRoute('app_pessoa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pessoa/new.html.twig', [
            'pessoa' => $pessoa,
            'form' => $form,
        ]);
    }

    #[Route('/alterar_senha', name: 'app_change_password')]
    public function alterarSenha(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_pessoa_show',['id'=>$user->getPessoa()->getId()]);
        }

        return $this->render('pessoa/change_password.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_pessoa_show', methods: ['GET'])]
    public function show(Pessoa $pessoa): Response
    {
        return $this->render('pessoa/show.html.twig', [
            'pessoa' => $pessoa,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pessoa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pessoa $pessoa, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PessoaType::class, $pessoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pessoa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pessoa/edit.html.twig', [
            'pessoa' => $pessoa,
            'form' => $form,
        ]);
    }

}

<?php

namespace App\Controller;

use App\Entity\Possessions;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Users;
use App\Form\PossessionsType;
use App\Form\UsersType;
use App\Repository\PossessionsRepository;
use Doctrine\ORM\EntityManagerInterface;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'users.index')]
    public function index(Request $request, UsersRepository $repository): Response
    {
        $users = $repository->findAll();
        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }



    
    #[Route('/users/{id}', name: 'users.show', requirements: ['id' => '\d+'])]
    public function show(Request $request, int $id, UsersRepository $repository): Response
    {
         $users = $repository->find($id);
         $possessions = $users->getPossession();
         return $this->render('users/show.html.twig', [
             'user' => $users ,
             'possessions' => $possessions
         ]);
    }




    #[Route('/users/create', name: 'users.create')]
    public function create(Request $request, EntityManagerInterface $em){
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'L\'utilisateur a bien été créé');
            return $this->redirectToRoute('users.index');
        }
        return $this->render('users/create.html.twig', [
            'form' => $form
        ]);
    }



    #[Route('/users/{id}/edit', name: 'users.delete', methods: ['DELETE'])]
    public function remove(Users $user, EntityManagerInterface $em)
    {
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'L\'utilisateur a bien été supprimé');
        return $this->redirectToRoute('users.index');
    }

    #[Route('/possessions/{id}/edit', name: 'possessions.delete', methods: ['DELETE'])]
    public function delete(Possessions $possession, EntityManagerInterface $em, UsersRepository $repository, int $id)
    {
        //$users = $repository->find($id);
        $userId = $possession->getUsers()->getId();
        $em->remove($possession);
        $em->flush();
        $this->addFlash('success', 'La possession a bien été supprimée');
        return $this->redirectToRoute('users.show' , ['id' => $userId]);
    }





    #[Route('/users/{id}/edit', name: 'users.edit', methods: ['GET', 'POST'])]
    public function edit(Users $user, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', 'L\'utilisateur a bien été modifié');
            return $this->redirectToRoute('users.index');
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }






    #[Route('/possessions/{id}/create', name: 'possessions.create')]
    public function createPossession(Request $request, EntityManagerInterface $em, int $id, UsersRepository $usersrepo){
        $user = $usersrepo ->find($id);
        $possession = new Possessions();
        $possession->setUsers($user);
        $form = $this->createForm(PossessionsType::class, $possession);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($possession);
            $em->flush();
            $this->addFlash('success', 'L\'objet a bien été créé');
            return $this->redirectToRoute('users.index');
        }
        return $this->render('possessions/create.html.twig', [
            'form' => $form
        ]);
    }
}



<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(UserInterface $user, PokemonRepository $pr): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'user' => $user,
            'pokemons' => $user->getPokemon(),
        ]);
    }
}

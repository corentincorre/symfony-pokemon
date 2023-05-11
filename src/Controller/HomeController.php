<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(PokemonRepository $pr): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'pokemons' => $pr->findAll()
        ]);
    }

}

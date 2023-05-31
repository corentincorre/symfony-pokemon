<?php

namespace App\Controller;

use App\Entity\PokemonList;
use App\Repository\PokemonListRepository;
use App\Repository\PokedexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class PokedexController extends AbstractController
{
    #[Route('/pokedex', name: 'app_pokedex')]
    public function index(UserInterface $user, PokemonListRepository $plr, PokedexRepository $pkr): Response
    {
        $pokedex = $pkr->findBy(['user'=>$user]);
        $catched = [];
        foreach ($pokedex as $elem){

            $catched[] = $elem->getPokemon();
        }

        return $this->render('pokedex/index.html.twig', [
            'controller_name' => 'PokedexController',
            'user' => $user,
            'catched' => $catched,
            'pokemons' => $plr->findAll()
        ]);
    }
    #[Route('/pokedex/get', name: 'app_pokedex_get')]
    public function getPokemons(EntityManagerInterface $em) {
        die;
        $pokemons = [];
        for ($id = 1; $id <= 151; $id++) {
            $url = "https://pokeapi.co/api/v2/pokemon/".$id;
            $response = file_get_contents($url);
            $data = json_decode($response);
            $pokemons[] = [
                'name' => $data->name,
                'img' => $data->sprites->front_default,
            ];
            $pokemon = new PokemonList();

            $pokemon->setName($data->name);
            $pokemon->setImg($data->sprites->front_default);
            $pokemon->setNum($id);
            $em->persist($pokemon);
            $em->flush();
        }
        
        return 'Import Fini';
    }
}

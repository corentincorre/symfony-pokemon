<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokedexController extends AbstractController
{
    #[Route('/pokedex', name: 'app_pokedex')]
    public function index(): Response
    {
        return $this->render('pokedex/index.html.twig', [
            'controller_name' => 'PokedexController',
            'pokedex' => $this->getPokemons()
        ]);
    } 

    private function getPokemons() {
        $pokemons = [];
        for ($id = 1; $id <= 151; $id++) {
            $url = "https://pokeapi.co/api/v2/pokemon/".$id;
            $response = file_get_contents($url);
            $data = json_decode($response);
            $pokemons[] = [
                'name' => $data->name,
                'img' => $data->sprites->front_default,
            ];
        }
        
        return $pokemons;
    }
}

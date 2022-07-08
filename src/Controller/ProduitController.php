<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produits", name="app_produits")
     */
    public function index(ProduitRepository $produitRepo): Response
    {
        $produits = $produitRepo->findAll();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits
        ]);
    }

    /**
     * @Route("/produits/{id}", name="app_detail_produit", requirements={"id"="\d+"})
     */
    public function detail($id, ProduitRepository $produitRepo): Response
    {
        return $this->render('produit/detail.html.twig', [
            'produit' => $produitRepo->find($id)
        ]);
    }
}

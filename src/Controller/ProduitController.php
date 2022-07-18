<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisFormType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    public function detail($id, ProduitRepository $produitRepo, Request $request, EntityManagerInterface $manager): Response
    {
        $avis = new Avis;

        $form = $this->createForm(AvisFormType::class, $avis);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() )
        {
            $avis->setCreatedAt(new \DateTime())
                 ->setProduit($produitRepo->find($id));

            $manager->persist($avis);
            $manager->flush();

            $this->addFlash('success', "Votre avis a bien été posté!");

            return $this->redirectToRoute("app_detail_produit", [
                'id' => $id
            ]);
        }

        return $this->render('produit/detail.html.twig', [
            'produit' => $produitRepo->find($id),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/categories", name="app_categories")
     */
    public function categoriesAll(CategorieRepository $catRepo): Response
    {
        $categories = $catRepo->findAll();

        return $this->render("produit/categories.html.twig", [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/categorie/{id}", name="app_categorie_produits")
     */
    public function categorieProduit($id, CategorieRepository $catRepo): Response
    {
        $categorie = $catRepo->find($id);

        if(!$categorie)
        {
            throw new NotFoundHttpException("La catégorie demandé n'existe pas");
        }

        return $this->render("produit/categorie_produit.html.twig", [
            'categorie' => $categorie
        ]);
    }
}

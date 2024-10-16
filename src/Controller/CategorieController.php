<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieRepository;
use App\Entity\Categorie;
use App\Form\ModifierCategorieType;
use App\Form\SupprimeCategorieType;


class CategorieController extends AbstractController
{
    #[Route('/liste-categories', name: 'app_liste_categories')]
    public function listeCategories(Request $request,CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        $form = $this->createForm(SupprimeCategorieType::class, null, [
            'categories' => $categories
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedCategories = $form->get('categories')->getData();
            foreach ($selectedCategories as $categorie) {
            $em->remove($categorie);
            }
            $em->flush();
            $this->addFlash('notice', 'Catégories supprimées avec succès');
            return $this->redirectToRoute('app_liste_categories');
            }
        return $this->render('categorie/liste-categories.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier-categorie/{id}', name: 'app_modifier_categorie')]    
    public function modifierCategorie(Request $request,Categorie $categorie,EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ModifierCategorieType::class, $categorie);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
            $em->persist($categorie);
            $em->flush();
            $this->addFlash('notice','Catégorie modifiée');
            return $this->redirectToRoute('app_liste_categories');
            }
        }
        return $this->render('categorie/modifier-categorie.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/supprimer-categorie/{id}', name: 'app_supprimer_categorie')]
    public function supprimerCategorie(Request $request,Categorie $categorie,EntityManagerInterface $em): Response
    {
        if($categorie!=null){
            $em->remove($categorie);
            $em->flush();
            $this->addFlash('notice','Catégorie supprimée');
        }
        return $this->redirectToRoute('app_liste_categories');
    }
}

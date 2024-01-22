<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Tag;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'admin_product')]
    #[Route('/{id}', name: 'admin_product_update')]
    public function index(ProductRepository $repository, Request $request, EntityManagerInterface $manager, $id = null): Response
    {
        // AFFICHAGE DES PRODUITS
        $products = $repository->findAll();
        // récupérer la liste des produits (SELECT * FROM product)
        
        // MODIFICATION DU PRODUCT
        if($id){
            $product = $repository->find($id);
            
        } else {
            $product = new Product();
        }
         // AJOUT DU PRODUCT

         // génération du formulaire à partir de la classe ProductType
         $form = $this->createForm(ProductType::class, $product);

         // ici on va gérer la requête entrante
         $form->handleRequest($request);
 
         // on va vérifier si le formulaire a été soumis et est valide
         if ($form->isSubmitted() && $form->isValid()){
 
             // on récupère les valeurs du formulaire
             
             $product = $form->getData();
 
             // on persiste les valeurs
 
             $manager->persist($product);
 
             // on exécute la transaction
             $manager->flush();
 
             // ensuite on redirige vers la route admin_product
             return $this->redirectToRoute('admin_product');
             }
           
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
            'form' => $form->createView()
        ]);
    }
     // SUPPRESSION DES PRODUITS
    #[Route('/delete/{id}', name: 'admin_product_delete')]
    public function delete(ProductRepository $repository, EntityManagerInterface $manager, $id = null):Response
    {   if($id)
        {
            $product = $repository->find($id);
            $manager->remove($product);
            $manager->flush();
            
            return $this->redirectToRoute('admin_product');
        }
    
    }
}

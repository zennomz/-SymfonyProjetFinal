<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'get_product', methods: 'GET')]
    public function getProducts(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();
        return $this->json($products);
    }

    #[Route('/product', name: 'create_product', methods: 'POST')]
    public function createProduct(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $em->persist($product);
        $em->flush();
        return $this->json($product);
    }

    #[Route('/product/update', name: 'update_product', methods: ['POST'])]
    public function updateProduct(Request $request, EntityManagerInterface $em, ProductRepository $productRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $product = $productRepository->find($data['id']);
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $em->flush();
        return $this->json($product);
    }

    #[Route('/product/delete', name: 'delete_product', methods: 'POST')]
    public function deleteProduct(Request $request, EntityManagerInterface $em, ProductRepository $productRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $product = $productRepository->find($data['id']);
        $productName = $product->getName();
        $productPrice = $product->getPrice();
        $em->remove($product);
        $em->flush();

        return $this->json("Le produit $productName avec un prix de $productPrice a été supprimé.");
    }
}

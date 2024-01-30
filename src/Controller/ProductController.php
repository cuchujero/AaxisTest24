<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use App\Security\TokenAuthenticator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;


class ProductController extends AbstractController
{
    private $serializer;
    private $tokenAuthenticator;

    public function __construct(SerializerInterface $serializer, TokenAuthenticator $tokenAuthenticator)
    {
        $this->serializer = $serializer;
        $this->tokenAuthenticator = $tokenAuthenticator;
    }
    
    /**
     * @Rest\Get("/v1/test")
     */
    public function test() {
        $data = ['message' => 'works'];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/v1/products")
     */
    public function loadProducts(Request $request, ManagerRegistry $doctrine): Response
    {
        
        $em = $doctrine->getManager();
        $user = $this->tokenAuthenticator->authenticateToken($request);

        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        try {
            foreach ($data as $productData) {
                $product = new Product();
                $product->setSku($productData['sku']);
                $product->setProductName($productData['product_name']);
                $product->setDescription($productData['description']);
                $product->setCreatedAt(new \DateTime());
                $product->setUpdatedAt(new \DateTime());
                $em->persist($product);
                $product->setSku($productData['sku']);
            }
            $em->flush();
            return new JsonResponse(['message' => 'Products loaded successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Rest\Put("/v1/products")
     */
    public function updateProducts(Request $request, ManagerRegistry $doctrine): Response
    {

        $em = $doctrine->getManager();
        $user = $this->tokenAuthenticator->authenticateToken($request);

        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        try {
            foreach ($data as $productData) {
                $product = $em->getRepository(Product::class)->findOneBy(['sku' => $productData['sku']]);

                if (!$product) {
                    throw new \Exception("Product with SKU {$productData['sku']} not found");
                }

                if ($productData['product_name']){
                    $product->setProductName($productData['product_name']);
                }

                if ($productData['description']){
                    $product->setDescription($productData['description']);
                }
              
                $product->setUpdatedAt(new \DateTime());

                $em->persist($product);
            }

            $em->flush();
            return new JsonResponse(['message' => 'Products updated successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }     
    }

    /**
     * @Rest\Get("/v1/products")
     */
        public function listProducts(Request $request, ManagerRegistry $doctrine): Response
        {
            $user = $this->tokenAuthenticator->authenticateToken($request);
            if (!$user) {
                return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            }
            $products = $doctrine->getManager()->getRepository(Product::class)->findAll();
            $serializedProducts = $this->serializer->serialize($products, 'json');
            return new JsonResponse($serializedProducts, Response::HTTP_OK, [], true);
        }
}

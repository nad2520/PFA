<?php

namespace App\Service;

use App\Repository\ProductRepository;

class ProductService
{
    private $productRepository;

    public function __construct($config)
    {
        $this->productRepository = new ProductRepository($config);
    }

    // =========================
    // 📦 GET ALL PRODUCTS
    // =========================
    public function getAllProducts()
    {
        $products = $this->productRepository->findAll();

        // logique métier simple : retourner tel quel
        return $products;
    }

    // =========================
    // 🔍 GET PRODUCT BY ID
    // =========================
    public function getProduct($id)
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return null;
        }

        return $product;
    }

    // =========================
    // 📊 GET AVAILABLE PRODUCTS
    // =========================
    public function getAvailableProducts()
    {
        $products = $this->productRepository->findAll();

        $available = [];

        foreach ($products as $product) {
            if ($product['stock'] > 0) {
                $available[] = $product;
            }
        }

        return $available;
    }
}
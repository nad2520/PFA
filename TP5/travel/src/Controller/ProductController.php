<?php

namespace App\Controller;

use App\Service\ProductService;

class ProductController
{
    private $productService;

    public function __construct($config)
    {
        $this->productService = new ProductService($config);
    }

    // =========================
    // 📦 LIST PRODUCTS
    // =========================
    public function index()
    {
        $products = $this->productService->getAllProducts();

        require __DIR__ . '/../../views/products.view.php';
    }

    // =========================
    // 🔍 SHOW PRODUCT
    // =========================
    public function show()
    {
        $id = $_GET['id'] ?? null;

        $product = $this->productService->getProduct($id);

        require __DIR__ . '/../../views/product.view.php';
    }
}
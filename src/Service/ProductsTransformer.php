<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;

class ProductsTransformer
{

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->em = $managerRegistry->getManager();
    }

    /**
     * @param $products
     * @param $parentCategory
     * @return void
     */
    public function writeToDb($products, $parentCategory): void
    {

        // Обновляем товары
        $this->em->getRepository(Product::class)->deleteByCategory($parentCategory);

        $products = $products['Products']['Product'];

        // Дополняем если только 1 элемент
        if (!isset($products[0])){
            $products = [0=>$products];
        }

        foreach ($products as $product) {
            $cat = new Product();
            $cat->setMgcId($product['Id']);
            $cat->setName($product['Name']);
            $cat->setPrice($product['Price']);
            $cat->setDealerId($product['DealerID']);
            $cat->setInStock($product['InStock']);
            $cat->setAvailable($product['Available']);
            $cat->setDownloadable($product['Downloadable']);
            $cat->setPicture($product['Picture']);
            $cat->setCategory($product['Category']);
            $cat->setParentCategory($parentCategory);
            $this->em->persist($cat);
        }

        $this->em->flush();
    }

}
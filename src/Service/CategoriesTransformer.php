<?php

namespace App\Service;

use App\Entity\Categories;
use Doctrine\Persistence\ManagerRegistry;

class CategoriesTransformer
{

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->em = $managerRegistry->getManager();
    }

    /**
     * @param $categories
     * @return void
     */
    public function writeToDb($categories): void
    {
        $this->em->getRepository(Categories::class)->deleteAllValues();

        $categories = $categories['Categories']['Category'];

        // Дополняем если только 1 элемент
        if (!isset($categories[0])){
            $categories = [0=>$categories];
        }

        foreach ($categories as $category) {
            $cat = new Categories();
            $cat->setName($category['name']);
            $cat->setTotalProducts($category['totalProducts']);
            $cat->setMgcId($category['@attributes']['id']);

            $parentId = $category['@attributes']['parentId'] ?? null;
            $cat->setParentId($parentId);

            $this->em->persist($cat);
        }

        $this->em->flush();
    }
}
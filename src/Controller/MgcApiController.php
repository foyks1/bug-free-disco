<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Product;
use App\Service\MgcApiService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MgcApiController extends AbstractController
{

    private MgcApiService $mgc;
    protected $cache;

    public function __construct(MgcApiService $mgc, ManagerRegistry $managerRegistry)
    {
        $this->mgc = $mgc;
        $this->em = $managerRegistry->getManager();
        $this->cache = RedisAdapter::createConnection('redis://redis:6379');
    }

    /**
     * @Route("/")
     */
    public function getCategories(): Response
    {
        //Если уже был запрос категорий, то возвращаем что есть в бд вместо вызова апи
        if ($this->cache->get('categories')) {
            $categories = $this->em->getRepository(Categories::class)->findByNot('totalProducts', 0);
        }
        else{
            $categories = $this->mgc->getCategories();
            // Предположим, что категории обновляются раз в час
            $this->cache->set('categories', 1, ['ex' => 3600]);
        }


        return $this->render('categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/getProduct/{category}", name="get_product")
     */
    public function getProduct(Categories $category): Response
    {

        //Если уже был запрос категорий, то возвращаем что есть в бд вместо вызова апи
        if ($this->cache->get('product'.$category->getMgcId())) {
            $products = $this->em->getRepository(Product::class)->findBy(['parentCategory' => $category->getMgcId()]);
        }
        else{
            $products = $this->mgc->getProduct($category);
            // Предположим, что продукты обновляются раз в час
            $this->cache->set('product'.$category->getMgcId(), 1, ['ex' => 3600]);
        }


        return $this->render('product.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/getProduct/info/{product}", name="get_product_info")
     */
    public function getProductInfo(Product $product): Response
    {
        return $this->render('productInfo.html.twig', [
            'product' => $product,
        ]);
    }

}
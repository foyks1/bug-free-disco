<?php

namespace App\Service;

use App\Entity\Categories;
use App\Entity\Product;
use App\Service\CategoriesTransformer;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\ProductsTransformer;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MgcApiService
{
    private HttpClientInterface $client;

    protected int $id;
    protected string $login = 'openbroker';
    protected string $password = 'yw4Tb8vK';
    protected string $url = 'https://test.mgc-loyalty.ru/v1/';

    private XmlService $xmlService;
    private CategoriesTransformer $catTransformer;
    private ProductsTransformer $prodTransformer;
    private ObjectManager $em;

    public function __construct(HttpClientInterface $client, ManagerRegistry $managerRegistry, XmlService $xmlService, CategoriesTransformer $catTransformer, ProductsTransformer $prodTransformer)
    {
        $this->id = rand(0, 10000);

        $this->client = $client;
        $this->em = $managerRegistry->getManager();
        $this->xmlService = $xmlService;
        $this->catTransformer = $catTransformer;
        $this->prodTransformer = $prodTransformer;
    }


    /**
     * @param string $methodName
     * @return array
     */
    public function getAuthArr(string $methodName): array
    {
        $auth = [
            'Login' => $this->login,
            'TransactionID' => $this->id,
            'MethodName' => $methodName,
            'Hash' => md5($this->id . $methodName . $this->login . $this->password),
        ];

        return $auth;
    }

    public function getCategories(): array
    {
        $methodName = 'GetCategories';

        $body = $this->xmlService->generateRequestXml($this->getAuthArr($methodName));

        $response = $this->client->request('POST', $this->url . $methodName, ['body' => $body]);

        $categories = $this->xmlService->convertXmlToArray($response->getContent());
        $this->catTransformer->writeToDb($categories);

        return $this->em->getRepository(Categories::class)->findByNot('totalProducts', 0);

    }

    public function getProduct(Categories $category): array
    {
        $methodName = 'GetProduct';
        $params = ['Category' => $category->getMgcId()];

        $body = $this->xmlService->generateRequestXml($this->getAuthArr($methodName), $params);

        $response = $this->client->request('POST', $this->url . $methodName, ['body' => $body]);

        $categories = $this->xmlService->convertXmlToArray($response->getContent());

        $this->prodTransformer->writeToDb($categories, $category->getMgcId());

        return $this->em->getRepository(Product::class)->findBy(['parentCategory' => $category->getMgcId()]);
    }


}
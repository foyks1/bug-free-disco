<?php

namespace App\Service;

use SimpleXMLElement;

class XmlService
{
    public function generateRequestXml($authArr, $params = []): string
    {

        dump($authArr);
        $xmlstr = <<<XML
            <?xml version='1.0' encoding="UTF-8" standalone='yes'?>
            <Request>
            </Request>
            XML;

        $xml = new SimpleXMLElement($xmlstr);

        $this->populateXml($xml, $authArr, 'Authentication');

        $xml->addChild('Parameters');
        $parameters = $xml->Parameters;

        $this->populateXml($parameters, $params, 'Categories');

        return $xml->asXML();
    }

    /**
     * @param SimpleXMLElement $xml
     * @param array $auth
     * @return bool
     */
    public function populateXml(SimpleXMLElement $xml, array $auth, string $elementName): bool
    {
        $xml->addChild($elementName);

        // Без флипа путаются местами ключ->значение
        $auth = array_flip($auth);
        // Заполняем xml от body
        $auth = array_walk_recursive($auth, array($xml->$elementName, 'addChild'));
        return $auth;
    }

    /**
     * @param string $response
     * @return mixed
     */
    public function convertXmlToArray(string $response): Array
    {
        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        return $array;
    }
}
<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Controller\LouvreController;


class ControllerUrlTest extends WebTestCase
{
  /**
  * @dataProvider urlProvider
  */
  public function testPageisSuccessful($url, $expectedStatus)
  {
    $client = self::createClient();
    $client->request('GET', $url);


    $this->assertSame($client->getResponse()->getStatusCode(),$expectedStatus);
  }

  public function urlProvider()
  {
    return array(
      array('/fr/', 200), // HTTP CONTENT : Status OK
      array('/fr/order', 200),
      array('/fr/information',302), // Redirection
      array('/fr/checkout',302),
      array('/fr/confirmation',302),
    );
  }
}

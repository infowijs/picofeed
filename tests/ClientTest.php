<?php

require_once 'lib/PicoFeed/Client.php';

use PicoFeed\Client;

class ClientTest extends PHPUnit_Framework_TestCase
{
    public function testDownload()
    {
        $client = Client::create();
        $client->url = 'http://petitcodeur.fr/robots.txt';
        $client->execute();

        $this->assertTrue($client->isModified());
        $this->assertNotEmpty($client->getContent());
        $this->assertNotEmpty($client->getEtag());
        $this->assertNotEmpty($client->getLastModified());
    }


    public function testCacheEtag()
    {
        $client = Client::create();
        $client->url = 'http://petitcodeur.fr/robots.txt';
        $client->execute();
        $etag = $client->getEtag();

        $client = Client::create();
        $client->url = 'http://petitcodeur.fr/robots.txt';
        $client->setEtag($etag);
        $client->execute();

        $this->assertFalse($client->isModified());
    }


    public function testCacheLastModified()
    {
        $client = Client::create();
        $client->url = 'http://petitcodeur.fr/feed.xml';
        $client->execute();
        $lastmod = $client->getLastModified();

        $client = Client::create();
        $client->url = 'http://petitcodeur.fr/robots.txt';
        $client->setLastModified($lastmod);
        $client->execute();

        $this->assertFalse($client->isModified());
    }


    public function testCacheBoth()
    {
        $client = Client::create();
        $client->url = 'http://petitcodeur.fr/robots.txt';
        $client->execute();
        $lastmod = $client->getLastModified();
        $etag = $client->getEtag();

        $client = Client::create();
        $client->url = 'http://petitcodeur.fr/robots.txt';
        $client->setLastModified($lastmod);
        $client->setEtag($etag);
        $client->execute();

        $this->assertFalse($client->isModified());
    }
}
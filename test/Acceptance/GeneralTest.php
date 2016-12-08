<?php
namespace Acceptance;

use Steps\PHPUnit\WebPageTestCase;

class GeneralTest extends WebPageTestCase
{
    public function testVisitAndSee()
    {
        $this->visit('http://localhost:4242/index.php')
            ->shouldSee('Test Homepage');
    }

    public function testVisitClickAndSee()
    {
        $this->visit('http://localhost:4242/index.php')
            ->click('Link')
            ->shouldSee('Second Page');
    }

    public function testVisitClickAndUrl()
    {
        $this->visit('http://localhost:4242/index.php')
            ->click('Link')
            ->shouldBeOnUrl('http://localhost:4242/second.php');
    }

    public function testShouldBeOK()
    {
        $this->visit('http://localhost:4242/index.php')
            ->shouldBeOK();
    }

    public function testShouldBeStatusCode()
    {
        $this->visit('http://localhost:4242/404.php')
            ->shouldBeStatusCode(404);
    }

}
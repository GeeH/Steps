<?php

namespace Steps\PHPUnit;

use aik099\PHPUnit\BrowserTestCase;
use Behat\Mink\Session;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

abstract class WebPageTestCase extends BrowserTestCase
{
    const PHP_LOCATION = '/usr/local/bin/php';
    const SERVER_IP = '127.0.0.1';
    const SERVER_PORT = '4242';

    public static $browsers = array(
        array(
            'driver' => 'goutte',
            'browserName' => 'firefox',
        ),
    );

    /**
     * @var Session
     */
    private $session;
    /**
     * @var Process
     */
    private $process;

    public function setUp()
    {
        parent::setUp();

        $this->process = new ProcessBuilder([
            self::PHP_LOCATION,
            '-S',
            self::SERVER_IP . ':' . self::SERVER_PORT,
        ]);
        $this->process->setWorkingDirectory('test/asset/public/');
        $this->process = $this->process->getProcess();
        $this->process->start();
        usleep(500000);
    }


    protected function tearDown()
    {
        $this->process->stop();
        parent::tearDown();
    }

    public function visit(string $url) : self
    {
        $this->session = $this->getSession();
        $this->session->visit($url);

        return $this;
    }

    public function shouldBeOK() : self
    {
        self::assertEquals(200, $this->session->getStatusCode());

        return $this;
    }

    public function shouldSee(string $textToSee) : self
    {
        self::assertTrue($this->session->getPage()->hasContent($textToSee));

        return $this;
    }

    public function shouldBeOnUrl(string $url) : self
    {
        self::assertEquals($this->getSession()->getCurrentUrl(), $url,
            "Expecting URL `{$url}` got `{$this->getSession()->getCurrentUrl()}`");

        return $this;
    }

    public function shouldBeOnUrlNotContaining(string $textUrlShouldNotContain)
    {
        $textUrlShouldNotContain = preg_quote($textUrlShouldNotContain);
        $url = $this->getSession()->getCurrentUrl();
        self::assertNotRegExp('#' . $textUrlShouldNotContain . '#', $url,
            "Expecting URL should not contain `{$textUrlShouldNotContain}` got `{$url}`");

        return $this;
    }

    public function shouldBeOnUrlContaining(string $textUrlShouldContain) : self
    {
        $url = $this->getSession()->getCurrentUrl();
        self::assertRegExp('#' . $textUrlShouldContain . '#', $url,
            "Expecting URL should contain `{$textUrlShouldContain}` got `{$url}`");

        return $this;
    }

    public function shouldBeStatusCode(int $statusCode) : self
    {
        self::assertEquals($statusCode, $this->getSession()->getStatusCode());

        return $this;
    }

    public function click(string $textToClick, string $parentItem = null) : self
    {
        if ($this->getSession()->getPage()->hasLink($textToClick)) {
            $this->getSession()->getPage()->clickLink($textToClick);
            return $this;
        }

        if ($this->getSession()->getPage()->hasButton($textToClick)) {
            $this->getSession()->getPage()->pressButton($textToClick);
            return $this;
        }

        if ($this->getSession()->getPage()->hasUncheckedField($textToClick)) {
            return $this->check($textToClick);
        }

        if ($this->getSession()->getPage()->hasCheckedField($textToClick)) {
            return $this->uncheck($textToClick);
        }

        if ($this->getSession()->getPage()->hasSelect($parentItem)) {
            return $this->select($textToClick, $parentItem);
        }
    }

    public function fill(string $formElementName, string $textToType) : self
    {
        $this->getSession()->getPage()->fillField($formElementName, $textToType);

        return $this;
    }

    public function check(string $formElementName) : self
    {
        $this->getSession()->getPage()->checkField($formElementName);

        return $this;
    }

    public function uncheck(string $formElementName) : self
    {
        $this->getSession()->getPage()->uncheckField($formElementName);

        return $this;
    }

    private function select(string $selectValue, string $selectElement) : self
    {
        $this->getSession()->getPage()->selectFieldOption($selectElement, $selectValue);

        return $this;
    }

}
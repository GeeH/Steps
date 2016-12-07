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
        usleep(100000);
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
        if ($this->session->getStatusCode() !== 200) {
            throw new \Exception('200 status code expected, ' . $this->session->getStatusCode() . ' returned with the message `'
                . $this->getStatusMessage() . '`');
        }

        return $this;
    }

    public function shouldSee(string $textToSee) : self
    {
        self::assertTrue($this->session->getPage()->hasContent($textToSee));

        return $this;
    }

    public function shouldBeOnUrl(string $url) : self
    {
        self::assertEquals($this->getSession()->getCurrentUrl(), $url);

        return $this;
    }

    public function click(string $textToClick) : self
    {
        $this->getSession()->getPage()->clickLink($textToClick);

        return $this;
    }
}
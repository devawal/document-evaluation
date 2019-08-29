<?php

namespace Tests\AppBundle\Command;

use AppBundle\Command\DocumentCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\EventListener\TestSessionListener as BaseTestSessionListener;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\SessionTest;

class DocumentCommandTest extends KernelTestCase {

    /**
     * Test Execute
     *
     */
    public function testExecute() {
        $kernel = static::createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new DocumentCommand($kernel->getContainer()));

        $command = $application->find('identification-requests:process');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'file' => 'input.csv'
        ));

        $expected = 'valid
valid
valid
document_number_length_invalid
request_limit_exceeded
valid
document_is_expired
valid
document_type_is_invalid
valid
valid
document_number_invalid
valid
document_issue_date_invalid
';
        $testresult = $commandTester->getDisplay();

        $this->assertEquals(preg_replace('/[^A-Za-z0-9\-]/', '', $expected), preg_replace('/[^A-Za-z0-9\-]/', '', $testresult));
    }

}

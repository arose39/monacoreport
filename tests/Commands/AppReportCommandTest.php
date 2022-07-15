<?php declare(strict_types=1);

use MonacoReport\Commands\AppReportCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class AppReportCommandTest extends TestCase
{
    private Command $command;

    protected function setUp(): void
    {
        $application = new Application();
        $application->add(new AppReportCommand());
        $this->command = $application->find('app:report');
    }

    protected function tearDown(): void
    {
        unset($application);
        unset($this->command);
    }

    public function testShowListAscOrder(): void
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            '--files' => 'resources'
        ]);
        $output = $commandTester->getDisplay();
        $needle = '15   Brendon Hartley     SCUDERIA TORO ROSSO HONDA   1:01:13.179';
        $this->assertStringContainsString($needle, $output);
    }

    public function testShowListDescOrder(): void
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            '--files' => 'resources',
            '--sort_order' => 'DESC'
        ]);
        $output = $commandTester->getDisplay();
        $needle = '15   Daniel Ricciardo    RED BULL RACING TAG HEUER   0:57:12.013';
        $this->assertStringContainsString($needle, $output);
    }

    public function testShowDriverInfo(): void
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            '--files' => 'resources',
            '--driver' => 'Kimi Räikkönen'
        ]);
        $output = $commandTester->getDisplay();
        $needle1 = '1:01:12.639';
        $needle2 = 'FERRARI';
        $needleOfOtherDriver = 'MCLAREN RENAULT';
        $this->assertStringContainsString($needle1, $output);
        $this->assertStringContainsString($needle2, $output);
        $this->assertStringNotContainsString($needleOfOtherDriver,$output);
    }

    public function testWrongFilesDirectory(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            '--files' => 'resourcccces',
        ]);
    }
}

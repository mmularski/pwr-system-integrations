<?php

namespace App\ProductUpdater\Console\Command\Worker;

use Magento\Framework\App\State;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\RabbitMq\Model\Service\AbstractService;
use App\ProductUpdater\Model\Service;
use App\RabbitMq\Logger\Logger;

/**
 * Class ProductUpdateWorker
 */
class ProductUpdateWorker extends AbstractWorker
{
    /**
     * @var Logger $logger
     */
    protected $logger;

    /**
     * @var Service $service
     */
    protected $service;

    /**
     * ProductWorkerCommand constructor.
     *
     * @param State $state
     * @param Logger $logger
     * @param Service $service
     */
    public function __construct(State $state, Logger $logger, Service $service)
    {
        $this->service = $service;
        $this->logger = $logger;

        parent::__construct($state);
    }

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('rabbitmq:worker:updater')
            ->setDescription('Consumes messages from queue');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     *
     * @return void;
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initExecute($input, $output);

        $exitCode = $this->service->getConsumer()->consume();

        switch ($exitCode) {
            case AbstractService::EXIT_CODE_SUCCESS:
                $this->printInfo('Worker executed successfully.');
                break;
            case AbstractService::EXIT_CODE_ERROR:
            default:
                $this->printError('An error occurred during consumer execution.');
                break;
        }
    }
}

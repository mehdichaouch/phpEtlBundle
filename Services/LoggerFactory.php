<?php

namespace Oliverde8\PhpEtlBundle\Services;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Oliverde8\PhpEtlBundle\Entity\EtlExecution;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    private ChainWorkDirManager $chainWorkDirManager;

    private Logger $etlLogger;

    public function __construct(ChainWorkDirManager $chainWorkDirManager, Logger $etlLogger)
    {
        $this->chainWorkDirManager = $chainWorkDirManager;
        $this->etlLogger = $etlLogger;
    }

    public function get(EtlExecution $execution): LoggerInterface
    {
        $logger = new Logger('etl');
        $logPath = $this->chainWorkDirManager->getLocalTmpWorkDir($execution);
        $logger->pushHandler(new StreamHandler("$logPath/execution.logger", Logger::INFO));

        foreach ($this->etlLogger->getHandlers() as $handler) {
            $logger->pushHandler($handler);
        }

        return $logger;
    }

}
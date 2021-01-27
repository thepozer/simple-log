<?php
namespace Thepozer\Log;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 *  Logger class with timestamp on lines
 *
 */
class SimpleLog extends AbstractLogger {

    private $arLevels = array(
        LogLevel::EMERGENCY => 1,
        LogLevel::ALERT     => 2,
        LogLevel::CRITICAL  => 3,
        LogLevel::ERROR     => 4,
        LogLevel::WARNING   => 5,
        LogLevel::NOTICE    => 6,
        LogLevel::INFO      => 7,
        LogLevel::DEBUG     => 8,
    );

    private $hFileLog = null;
    private $iLogLevel = 4;

    public function __construct($hFileLog, $sLogLevel = LogLevel::ERROR) {
        $this->hFileLog  = $hFileLog;
        $this->iLogLevel = $this->arLevels[$sLogLevel];
    }

    public function setLogLevel($sLogLevel) {
        if (array_key_exists($sLogLevel, $this->arLevels)) {
            $this->iLogLevel = $this->arLevels[$sLogLevel];
        } else {
            throw new \Psr\Log\InvalidArgumentException("Not defined Log level ....");
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed   $level
     * @param string  $message
     * @param mixed[] $context
     *
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function log($sLevel, $sMessage, array $arContext = array()) {
        if (!isset($this->arLevels[$sLevel])) {
            throw new \Psr\Log\InvalidArgumentException("Not defined Log level : '{$sLevel}' ....");
        }

        if ($this->arLevels[$sLevel] <= $this->iLogLevel) {
            if (!fwrite($this->hFileLog, '[' . date('Y-m-d H:i:s O') . '] ' . $sLevel . ' : ' . $sMessage . PHP_EOL)) {
                throw new \Exception("Can't Write in log file ....");
            }
        }
    }
}

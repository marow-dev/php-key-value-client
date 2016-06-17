<?php
namespace KVScktClient;

class CommandException extends \Exception {
    /**
     * Name of command that caused error
     * @access protected
     * @var string
     */
    protected $command;

    public function __construct($message, $command = '') {
        parent::__construct($message);
        $this->command = $command;
    }

    /**
     * Return command that caused error
     * @return string
     */
    public function getCommand() {
        return $this->command;
    }
}

<?php

class Mailer {
    public function __construct(private Logger $logger) {}

    public function send(string $to): void {
        $this->logger->log("Sending email to $to");
    }
}
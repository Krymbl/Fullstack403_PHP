<?php

class UserService {
    public function __construct(private Mailer $mailer, private Logger $logger) {}

    public function register(string $email): void {
        $this->logger->log("Registering $email");
        $this->mailer->send($email);
    }
}
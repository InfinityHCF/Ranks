<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\credentialsengine\session;


use sergittos\credentialsengine\CredentialsEngine;

class BaseSession {

    protected string $lowercase_name;
    protected string $username;
    protected string $rank_id;

    public function __construct(string $username) {
        $this->username = $username;
        $this->lowercase_name = strtolower($username);
    }

    public function getLowercaseName(): string {
        return $this->lowercase_name;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getRankId(): string {
        return $this->rank_id;
    }

    public function setRankId(string $rank_id): void {
        $this->rank_id = $rank_id;
    }

    public function load(): void {
        CredentialsEngine::getInstance()->getProvider()->loadSession($this);
    }

    public function save(): void {
        CredentialsEngine::getInstance()->getProvider()->saveSession($this);
    }

}
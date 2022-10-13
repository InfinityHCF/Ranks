<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\credentialsengine\provider;


use sergittos\credentialsengine\session\BaseSession;
use sergittos\credentialsengine\session\Session;

abstract class Provider {

    abstract public function loadSession(BaseSession $session): void;

    abstract public function saveSession(BaseSession $session): void;

    abstract public function setRank(BaseSession $session): void;

    abstract public function checkRank(Session $session): void;

}
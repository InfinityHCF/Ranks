<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\credentialsengine\provider\mysql\task;


use sergittos\credentialsengine\provider\mysql\MysqlProvider;
use sergittos\credentialsengine\session\BaseSession;
use mysqli;

class SaveSessionTask extends MysqlTask {

    private string $username;
    private string $rank_id;

    public function __construct(MysqlProvider $provider, BaseSession $session) {
        $this->username = $session->getLowercaseName();
        $this->rank_id = $session->getRankId();
        parent::__construct($provider);
    }

    public function execute(mysqli $mysqli): void {
        $rank_id = $this->rank_id;
        $username = $this->username;
        
        $statement = $mysqli->prepare("UPDATE users SET rank_id = ? WHERE username = ?");
        $statement->bind_param("ss", $rank_id, $username);
        $statement->execute();
    }

}
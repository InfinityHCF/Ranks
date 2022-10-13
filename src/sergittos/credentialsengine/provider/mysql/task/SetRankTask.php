<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\credentialsengine\provider\mysql\task;


use mysqli;
use sergittos\credentialsengine\provider\mysql\MysqlProvider;
use sergittos\credentialsengine\session\BaseSession;

class SetRankTask extends MysqlTask {

    private string $username;
    private string $rank_id;

    public function __construct(MysqlProvider $provider, BaseSession $session, string $rank_id) {
        $this->username = $session->getLowercaseName();
        $this->rank_id = $rank_id;
        parent::__construct($provider);
    }

    public function execute(mysqli $mysqli): void {
        $statement = $mysqli->prepare("INSERT INTO users (username, rank_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE rank_id = ?");
        $statement->bind_param("sss", ...[$this->username, $this->rank_id, $this->rank_id]);
        $statement->execute();
        $statement->close();
    }

}
<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\credentialsengine\command;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\utils\TextFormat;
use sergittos\credentialsengine\CredentialsEngine;
use sergittos\credentialsengine\session\SessionFactory;

class CheckRankCommand extends Command {

    public function __construct() {
        $this->setPermission(DefaultPermissions::ROOT_OPERATOR);
        parent::__construct("checkrank", "Check the rank of a player", "/checkrank <player>");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$this->testPermission($sender)) {
            return;
        } elseif(!isset($args[0])) {
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            return;
        }

        $session = SessionFactory::getSessionByName($args[0]);
        if($session !== null) {
            CredentialsEngine::getInstance()->getProvider()->checkRank($session);
        }
    }

}
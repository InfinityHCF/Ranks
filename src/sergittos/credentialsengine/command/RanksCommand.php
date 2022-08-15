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
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\player\Player;
use sergittos\credentialsengine\form\RanksForm;

class RanksCommand extends Command {

    public function __construct() {
        $manager = PermissionManager::getInstance();
        $manager->addPermission(new Permission($permission = "command.ranks"));
        $manager->getPermission(DefaultPermissions::ROOT_OPERATOR)->addChild($permission, true);

        $this->setPermission($permission);
        parent::__construct("ranks", "CredentialsEngine main command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if($this->testPermission($sender) and $sender instanceof Player) {
            $sender->sendForm(new RanksForm());
        }
    }

}
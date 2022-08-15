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
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use sergittos\credentialsengine\CredentialsEngine;
use sergittos\credentialsengine\session\SessionFactory;
use sergittos\credentialsengine\utils\ColorUtils;
use function count;

class SetRankCommand extends Command {

    public function __construct() {
        $this->setPermission(DefaultPermissions::ROOT_OPERATOR);
        parent::__construct("setrank", "Sets a rank to a player", "/setrank <player> <rank_id>");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$this->testPermission($sender)) {
            return;
        }

        if(count($args) < 2) {
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            return;
        }

        $target_session = SessionFactory::getOfflineSession($args[0]);
        $online_session = $target_session->getOnlineSession();
        if($online_session === null) {
            $target_session->load();
        }

        $rank = CredentialsEngine::getInstance()->getRankManager()->getRankById($args[1]);
        if($rank === null) {
            $sender->sendMessage(TextFormat::RED . "Rank with ID ($args[1]) was not found.");
            return;
        }

        if($online_session !== null) {
            $online_session->setRank($rank);
        } else {
            $target_session->setRankId($rank->getId());
            $target_session->save();
        }

        $username = $target_session->getUsername();
        $rank_name = $rank->getColoredName();
        $sender->sendMessage(TextFormat::GREEN . $username . " received " . $rank_name . TextFormat::RESET . TextFormat::GREEN . " successfully!");
        foreach(Server::getInstance()->getOnlinePlayers() as $player) {
            $player->sendMessage(ColorUtils::translate(
                "{AQUA}{BOLD}" . $username . " {RESET}{YELLOW}has purchased the rank {BOLD}" . $rank_name . "{RESET}{YELLOW} in {GRAY}infinitynetwork.tebex.io"
            ));
        }
    }

}
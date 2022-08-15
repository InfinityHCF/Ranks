<?php

declare(strict_types=1);

namespace sergittos\credentialsengine\command;

use abstractqueue\AbstractPlugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use sergittos\credentialsengine\CredentialsEngine;
use sergittos\credentialsengine\rank\Rank;
use sergittos\credentialsengine\session\Session;
use sergittos\credentialsengine\session\SessionFactory;
use sergittos\credentialsengine\utils\ColorUtils;

final class ListCommand extends Command {

    /**
     * @param CommandSender $sender
     * @param string        $commandLabel
     * @param array         $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        $ranks = CredentialsEngine::getInstance()->getRankManager()->getRanks();
        usort($ranks, fn(Rank $firstRank, Rank $secondRank) => $firstRank->getPriority() > $secondRank->getPriority() ? 1 : 0);

        $sender->sendMessage(TextFormat::RED);
        $sender->sendMessage(implode(TextFormat::WHITE . ', ', array_map(fn(Rank $rank) => ColorUtils::translate($rank->getColor() . $rank->getName()), $ranks)));

        $sessions = [];

        foreach (SessionFactory::getSessions() as $session) $sessions[] = $session;

        usort($sessions, fn(Session $firstSession, Session $secondSession) => $firstSession->getRank()->getPriority() > $secondSession->getRank()->getPriority() ? 1 : 0);

        $sender->sendMessage(TextFormat::colorize(sprintf(
            '&7(&3%s&7/&3%s&7) [%s&7]',
            count(Server::getInstance()->getOnlinePlayers()),
            AbstractPlugin::getInstance()->getMaxSlots(),
            implode(TextFormat::WHITE . ', ', array_map(fn(Session $session) => ColorUtils::translate(($session->getRank()->getId() === 'guest' ? TextFormat::WHITE : $session->getRank()->getColor()) . $session->getUsername()), $sessions))
        )));
        $sender->sendMessage(TextFormat::GREEN);
    }
}
<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\credentialsengine\session;


use pocketmine\player\Player;
use function strtolower;

class SessionFactory {

    /** @var Session[] */
    static private array $sessions = [];

    /**
     * @return Session[]
     */
    static public function getSessions(): array {
        return self::$sessions;
    }

    static public function getSession(Player $player): ?Session {
        return self::$sessions[strtolower($player->getName())] ?? null;
    }

    static public function getSessionByName(string $username): ?Session {
        return self::$sessions[strtolower($username)] ?? null;
    }

    static public function getOfflineSession(string $username): OfflineSession {
        return new OfflineSession($username);
    }

    static public function createSession(Player $player): void {
        self::$sessions[strtolower($player->getName())] = new Session($player);
    }

    static public function removeSession(Player $player): void {
        $session = self::$sessions[$username = strtolower($player->getName())];
        $session->save();

        unset(self::$sessions[$username]);
    }

}
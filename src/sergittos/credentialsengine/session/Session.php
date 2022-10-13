<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\credentialsengine\session;


use pocketmine\player\Player;
use pocketmine\plugin\PluginException;
use sergittos\credentialsengine\rank\Rank;
use sergittos\credentialsengine\CredentialsEngine;
use sergittos\credentialsengine\utils\ColorUtils;
use sergittos\credentialsengine\utils\ConfigGetter;
use sergittos\credentialsengine\utils\PermissionsUtils;

class Session extends BaseSession {

    private Player $player;
    private Rank $rank;

    public function __construct(Player $player) {
        $this->player = $player;
        $this->rank = CredentialsEngine::getInstance()->getRankManager()->getDefaultRank() ?? throw new PluginException('invalid default rank');

        parent::__construct($player->getName());

        CredentialsEngine::getInstance()->getProvider()->loadSession($this);
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function getRank(): Rank {
        return $this->rank;
    }

    public function getColoredRankName(): string {
        return $this->rank->getColoredName();
    }

    public function setRank(Rank $rank): void {
        $this->setRankId($rank->getId());
        $this->rank = $rank;

        // $this->updateNameTag();
        PermissionsUtils::updateSessionPermissions($this);
    }

    private function updateNameTag(): void {
        $this->player->setNameTag(ColorUtils::translate(str_replace(
            ["{rank}", "{player}"],
            [$this->getColoredRankName(), $this->player->getName()],
            ConfigGetter::getNameTagFormat()
        )));
    }

}
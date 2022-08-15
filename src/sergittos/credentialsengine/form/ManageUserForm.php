<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\credentialsengine\form;


use EasyUI\element\Input;
use EasyUI\utils\FormResponse;
use EasyUI\variant\CustomForm;
use pocketmine\player\Player;
use sergittos\credentialsengine\CredentialsEngine;
use sergittos\credentialsengine\session\SessionFactory;

class ManageUserForm extends CustomForm {

    public function __construct() {
        parent::__construct("Manage user");
    }

    protected function onCreation(): void {
        $this->addElement("username", new Input("Username:"));
    }

    protected function onSubmit(Player $player, FormResponse $response): void {
        $session = SessionFactory::getOfflineSession($response->getInputSubmittedText("username"));
        $session->load();

        $player->sendForm(new UserOptionsForm($session));
    }

}
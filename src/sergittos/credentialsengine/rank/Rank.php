<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\credentialsengine\rank;


class Rank {

    public function __construct(
        private string $id,
        private string $name,
        private int $priority,
        private string $color,
        private array $permissions
    ) {}

    public function getId(): string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPriority(): int {
        return $this->priority;
    }

    public function getColor(): string {
        return $this->color;
    }

    public function getColoredName(): string {
        return $this->color . $this->name;
    }

    /**
     * @return string[]
     */
    public function getPermissions(): array {
        return $this->permissions;
    }

}
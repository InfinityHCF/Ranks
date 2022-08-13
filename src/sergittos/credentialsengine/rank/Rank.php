<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\credentialsengine\rank;


class Rank {

    /**
     * @param string $id
     * @param string $name
     * @param string $color
     * @param array  $permissions
     * @param int    $priority
     */
    public function __construct(
        private string $id,
        private string $name,
        private string $color,
        private array $permissions,
        private int $priority
    ) {}

    public function getId(): string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getColor(): string {
        return $this->color;
    }

    /**
     * @return string[]
     */
    public function getPermissions(): array {
        return $this->permissions;
    }

    /**
     * @return int
     */
    public function getPriority(): int {
        return $this->priority;
    }
}
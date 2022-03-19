<?php

declare(strict_types=1);

namespace App\Auth\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTokenDTO
{
    /**
     * @Assert\NotBlank()
     */
    private string $username = '';

    /**
     * @Assert\NotBlank()
     */
    private string $password = '';

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
}

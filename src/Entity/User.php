<?php declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private string $role;

    private string $username;

    private int $id;

    private string $stateIso;

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $stateIso
     */
    public function setStateIso(string $stateIso): void
    {
        $this->stateIso = $stateIso;
    }

    public function getRoles()
    {
        return [$this->role, 'ROLE_USER'];
    }

    public function getPassword()
    {

    }

    public function getSalt()
    {

    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {

    }
}

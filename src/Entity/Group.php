<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    public function getId() { return $this->id; }

    // add your own fields
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    public function setName($name) { $this->name = $name; }
    public function getName() { return $this->name; }

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="group")
     */
    private $users;
    public function getUser() { return $this->users; }
    public function addUser(User $user) {
        $this->users[] = $user;
    }
}

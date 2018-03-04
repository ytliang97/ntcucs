<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
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
    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    /**
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    private $alias;
    public function getAlias() { return $this->alias; }
    public function setAlias($alias) { $this->alias = $alias; }


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }


    /**
     * @ORM\OneToMany(targetEntity="Member", mappedBy="team")
     */
    private $members;
    public function getMembers() { return $this->members; }
    public function setMembers($members) { $this->members = $members; }
    public function addMember($member) { $this->members[] = $member; }
}

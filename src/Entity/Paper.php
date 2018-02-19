<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaperRepository")
 */
class Paper
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    public function getId() { return $this->id; }

    private $name;
    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    // add your own fields
}

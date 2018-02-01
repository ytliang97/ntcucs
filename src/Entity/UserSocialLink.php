<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserSocialLinkRepository")
 */
class UserSocialLink
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
    private $type;
    public function setType($type) { $this->type = $type; }
    public function getType() { return $this->type; }

    /**
     * @ORM\Column(type="string")
     */
    private $url;
    public function setUrl($url) { $this->url = $url; }
    public function getUrl() { return $this->url; }
}

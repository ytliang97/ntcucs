<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;
    public function setFirstName($firstName) { $this->firstName = $firstName; }
    public function getFirstName() { return $this->firstName; }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $secondName;
    public function setSecondName($secondName) { $this->secondName = $secondName; }
    public function getSecondName() { return $this->secondName; }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;
    public function setTitle($title) { $this->title = $title; }
    public function getTitle() { return $this->title; }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $bio;
    public function setBio($bio) { $this->bio = $bio; }
    public function getBio() { return $this->bio; }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;
    public function setAvatar($avatar) { $this->avatar = $avatar; }
    public function getAvatar() { return $this->avatar; }

    /**
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="users_groups")
     */
    private $group;
    public function getGroup() { return $this->group; }

    /**
     * @ORM\ManyToMany(targetEntity="UserSocialLink")
     * @ORM\JoinTable(name="users_socialLinks",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="link_id", referencedColumnName="id")}
     * )
     */
    private $socialLink;
    public function addSocialLink() {
        $this->socialLink[] = $this->socialLink;
    }
    public function getSocialLink() { return $this->socialLink; }
}

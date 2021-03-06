<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 */
class Member
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
     * @ORM\Column(type="string")
     */
    private $title;
    public function getTitle() { return $this->title; }
    public function setTitle($title) { $this->title = $title; }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $officeNumber;
    public function getOfficeNumber() { return $this->officeNumber; }
    public function setOfficeNumber($officeNumber) { $this->officeNumber = $officeNumber; }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $cellPhone;
    public function getCellPhone() { return $this->cellPhone; }
    public function setCellPhone($cellPhone) { $this->cellPhone = $cellPhone; }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $website;
    public function getWebsite() { return $this->website; }
    public function setWebsite($website) { $this->website = $website; }

    /**
     * @ORM\Column(type="datetime")
     */
    private $createTime;
    public function getCreateTime() { return $this->createTime; }
    public function setCreateTime($createTime) { $this->createTime = $createTime; }

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateTime;
    public function getUpdateTime() { return $this->updateTime; }
    public function setUpdateTime($updateTime) { $this->updateTime = $updateTime; }

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="members")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $team;
    public function getTeam() { return $this->team; }
    public function setTeam($team) { $this->team = $team; }

    /**
     * @ORM\ManyToOne(targetEntity="PublicUploaded")
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $avatar;
    public function setAvatar($avatar) { $this->avatar = $avatar; }
    public function getAvatar() { return $this->avatar; }

    /**
     * @ORM\Column(type="integer")
     */
    private $memberOrder;
    public function setMemberOrder($order) { $this->memberOrder = $order; }
    public function getMemberOrder() { return $this->memberOrder; }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $major;
    public function setMajor($major) { $this->major = $major; }
    public function getMajor() { return $this->major; }
}

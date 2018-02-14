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
     * @ORM\JoinColumn(nullable=true)
     */
    private $team;
    public function getTeam() { return $this->team; }
    public function setTeam($team) { $this->team = $team; }
}

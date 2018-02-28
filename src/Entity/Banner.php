<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BannerRepository")
 */
class Banner
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
    private $title;
    public function getTitle() { return $this->title; }
    public function setTitle($title) { $this->title = $title; }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $subtitle;
    public function getSubtitle() { return $this->subtitle; }
    public function setSubtitle($subtitle) { $this->subtitle = $subtitle; }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $linkto;
    public function getLinkTo() { return $this->linkto; }
    public function setLinkTo($linkto) { $this->linkto = $linkto; }

    /**
     * @ORM\ManyToOne(targetEntity="PublicUploaded")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $image;
    public function getImage() { return $this->image; }
    public function setImage($image) { $this->image = $image; }

    /**
     * @ORM\Column(type="datetime")
     */
    private $createTime;
    public function getCreateTime() { return $this->createTime; }
    public function setCreateTime($createTime) { $this->createTime = $createTime; }
}

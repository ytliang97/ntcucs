<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlbumRepository")
 */
class Album
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }

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
     * @ORM\ManyToOne(targetEntity="PublicUploaded")
     * @ORM\JoinColumn(name="cover_id", referencedColumnName="id")
     */
    private $cover;
    public function getCover() { return $this->cover; }
    public function setCover($cover) { $this->cover = $cover; }

    /**
     * @ORM\ManyToMany(targetEntity="PublicUploaded")
     * @ORM\JoinTable(name="albums_assets",
     *     joinColumns={@ORM\JoinColumn(name="album_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="asset_id", referencedColumnName="id")}
     * )
     */
    private $content;
    public function getContent() { return $this->content; }
    public function addContent(PublicUploaded $asset) {
        $this->content[] = $asset;
    }

}

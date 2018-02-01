<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PublicAssetRepository")
 */
class PublicUploaded
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
     * @ORM\Column(type="string", unique=true)
     */
    private $hashName;
    public function getHashName() { return $this->hashName; }
    public function setHashName($hashName) { $this->hashName = $hashName; }

    /**
     * @ORM\Column(type="string")
     */
    private $fullPath;
    public function getFullPath() { return $this->fullPath; }
    public function setFullPath($fullPath) { $this->fullPath = $fullPath; }

    /**
     * @ORM\Column(type="string")
     */
    private $fileType;
    public function getFileType() { return $this->fileType; }
    public function setFileType($fileType) { $this->fileType = $fileType; }

    /**
     * @ORM\Column(type="datetime")
     */
    private $createTime;
    public function getCreateTime() { return $this->createTime; }
    public function setCreateTime($createTime) { $this->createTime = $createTime; }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;
    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }
}

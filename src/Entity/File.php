<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class File
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
    private $originName;
    public function getOriginName() { return $this->originName; }
    public function setOriginName($originName) { $this->originName = $originName; }

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $hashName;
    public function getHashName() { return $this->hashName; }
    public function setHashName($hashName) { $this->hashName = $hashName; }

    /**
     * @ORM\Column(type="string")
     */
    private $fileType;
    public function getFileType() { return $this->fileType; }
    public function setFileType($fileType) { $this->fileType = $fileType; }
}

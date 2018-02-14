<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileArchiveRepository")
 */
class FileArchive
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    public function getId() { return $this->id; }

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
     * @ORM\ManyToMany(targetEntity="File")
     * @ORM\JoinTable(name="archive_file",
     *     joinColumns={@ORM\JoinColumn(name="archive_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", onDelete="CASCADE")}
     *     )
     */
    private $files;
    public function getFiles() { return $this->files; }
    public function setFiles($files) { $this->files = $files; }
    public function addFile($file) { $this->files[] = $file; }

    // add your own fields
}

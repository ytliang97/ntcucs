<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page
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
     * @ORM\Column(type="string", unique=true)
     */
    private $alias;
    public function getAlias() { return $this->alias; }
    public function setAlias($alias) { $this->alias = $alias; }

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;
    public function getContent() { return $this->content; }
    public function setContent($content) { $this->content = $content; }

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
     * @ORM\JoinTable(name="pages_attachments",
     *     joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="attachment_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     * @ORM\OrderBy({"id"="DESC"})
     */
    private $attachments;
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }
    public function addAttachments(File $file) {
        $this->attachments[] = $file;
    }
    public function getAttachments() {
        return $this->attachments;
    }
}

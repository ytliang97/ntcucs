<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

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
     * @ORM\Column(type="string", nullable=true)
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
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $clickAmount;
    public function getClickAmount() { return $this->clickAmount; }
    public function incrementClickAmount() { $this->clickAmount += 1; }
    public function initClickAmount() { $this->clickAmount = 0; }

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinTable(name="posts_categories")
     */
    private $categories;
    public function addCategories(Category $category) {
        $category->addPosts($this);
        $this->categories[] = $category;
    }
    public function getCategories() {
        return $this->categories;
    }
    public function removeCategories(Category $category) {
        $this->categories->removeElement($category);
    }

    /**
     * @ORM\ManyToMany(targetEntity="File")
     * @ORM\JoinTable(name="posts_attachments",
     *     joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="attachment_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $attachments;
    public function addAttachments(File $file) {
        $this->attachments[] = $file;
    }
    public function getAttachments() {
        return $this->attachments;
    }
    public function setAttachments($attachments) {
        $this->attachments = $attachments;
    }
}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    public function __construct()
    {
        //$this->posts = new ArrayCollection();
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

    // add your own fields

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateTime;
    public function getUpdateTime() { return $this->updateTime; }
    public function setUpdatetime($updateTime) { $this->updateTime = $updateTime; }

    /**
     * @ORM\Column(type="datetime")
     */
    private $createTime;
    public function getCreateTime() { return $this->createTime; }
    public function setCreateTime($createTime) { $this->createTime = $createTime; }

    /**
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="categories")
     */
    private $posts;
    public function addPosts(Post $post) {
        $this->posts[] = $post;
    }
    public function getPosts() {
        return $this->posts;
    }
}

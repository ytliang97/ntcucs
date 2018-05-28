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
     * @ORM\Column(type="string", unique=true)
     */
    private $alias;
    public function getAlias() { return $this->alias; }
    public function setAlias($alias) { $this->alias = $alias; }

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
     * @ORM\OrderBy({"createTime" = "DESC"})
     */
    private $posts;
    public function addPosts(Post $post) {
        $this->posts[] = $post;
    }
    public function getPosts() {
        return $this->posts;
    }
}

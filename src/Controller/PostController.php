<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/1/31
 * Time: 上午 09:19
 */

namespace App\Controller;


use App\Entity\Category;
use App\Entity\File;
use App\Entity\Post;
use App\Form\Datatransformer\JsonToAttachmentTransformer;
use App\Form\Type\PostType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    public function create(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $currentTime = new \DateTime('now', new \DateTimeZone("Asia/Taipei"));
            $post->setCreateTime($currentTime);
            $post->setUpdateTime($currentTime);
            $post->initClickAmount();

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute("admin.post.list");
        }

        return $this->render("admin/post-editor.html.twig", array(
            "form" => $form->createView(),
            "post" => $post
        ));

    }

    public function edit(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $postsRepository = $em->getRepository(Post::class);
        $post = $postsRepository->find($id);

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post = $form->getData();

            $currentTime = new \DateTime('now', new \DateTimeZone("Asia/Taipei"));
            $post->setUpdateTime($currentTime);

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute("admin.post.list");
        }

        return $this->render("admin/post-editor.html.twig", array(
            "form" => $form->createView(),
            "post" => $post
        ));

    }

    public function delete(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $postsRepository = $em->getRepository(Post::class);
        $post = $postsRepository->find($id);

        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute("admin.post.list");
    }

    public function listAll(Request $request, $page) {

        $em = $this->getDoctrine()->getManager();
        $postsRepository = $em->getRepository(Post::class);
        $postsList = $postsRepository->findBy(array(), array("createTime"=>"DESC"));

        return $this->render("admin/posts-list.html.twig",
                             array("posts"=>$postsList));
    }

    public function show(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $postRepository = $em->getRepository(Post::class);
        $post = $postRepository->find($id);

        return $this->render("front/post-show.html.twig", array("post" => $post));

    }
}
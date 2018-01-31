<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/1/31
 * Time: 上午 09:19
 */

namespace App\Controller;


use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    public function create(Request $request) {

        $post = new Post();

        $form = $this->createFormBuilder($post)
            ->add("name", TextType::class, array("label"=>"文章標題"))
            ->add("content", TextareaType::class, array("label"=>"文章內容"))
            ->add("submit", SubmitType::class, array("label"=>"新增文章"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post = $form->getData();

            $currentTime = new \DateTime('now', new \DateTimeZone("Asia/Taipei"));
            $post->setCreateTime($currentTime);
            $post->setUpdateTime($currentTime);

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute("admin.post.list");
        }

        return $this->render("post-editor.html.twig", array(
            "form" => $form->createView()
        ));

    }

    public function edit(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $postsRepository = $em->getRepository(Post::class);
        $post = $postsRepository->find($id);

        $form = $this->createFormBuilder($post)
            ->add("name", TextType::class, array("label"=>"文章標題"))
            ->add("content", TextareaType::class, array("label"=>"文章內容"))
            ->add("submit", SubmitType::class, array("label"=>"更新文章"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post = $form->getData();

            $currentTime = new \DateTime('now', new \DateTimeZone("Asia/Taipei"));
            $post->setUpdateTime($currentTime);

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute("admin.post.list");
        }

        return $this->render("post-editor.html.twig", array(
            "form" => $form->createView()
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
        return $this->render("posts-list.html.twig");
    }
}
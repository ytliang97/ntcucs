<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/1/29
 * Time: 下午 09:17
 */

namespace App\Controller;

use App\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends Controller
{
    public function create(Request $request) {

        $posts = new Posts();

        $form = $this->createFormBuilder($posts)
            ->add("name", TextType::class, array("label"=>"文章標題"))
            ->add("content", TextareaType::class, array("label"=>"文章內容"))
            ->add("create", SubmitType::class, array("label"=>"新增文章"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $posts = $form->getData();

            $currentTime = new \DateTime('now', new \DateTimeZone("Asia/Taipei"));
            $posts->setCreateTime($currentTime);
            $posts->setUpdateTime($currentTime);

            $em = $this->getDoctrine()->getManager();
            $em->persist($posts);
            $em->flush();
        }

        return $this->render("post-editor.html.twig", array(
            "form" => $form->createView()
        ));

    }

    public function edit(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $postsRepository = $em->getRepository(Posts::class);
        $posts = $postsRepository->find($id);

        $form = $this->createFormBuilder($posts)
            ->add("name", TextType::class, array("label"=>"文章標題"))
            ->add("content", TextareaType::class, array("label"=>"文章內容"))
            ->add("create", SubmitType::class, array("label"=>"新增文章"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $posts = $form->getData();

            $currentTime = new \DateTime('now', new \DateTimeZone("Asia/Taipei"));
            $posts->setUpdateTime($currentTime);

            $em->persist($posts);
            $em->flush();
        }

        return $this->render("post-editor.html.twig", array(
            "form" => $form->createView()
        ));

    }

    public function delete(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $postsRepository = $em->getRepository(Posts::class);
        $posts = $postsRepository->find($id);

        $em->remove($posts);
        $em->flush();
        return $this->redirectToRoute("admin.posts.list");
    }

    public function listAll(Request $request, $page) {

    }
}
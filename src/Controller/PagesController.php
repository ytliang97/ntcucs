<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/1/29
 * Time: 下午 09:23
 */

namespace App\Controller;

use App\Entity\Pages;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PagesController extends Controller
{
    public function create(Request $request) {

        $pages = new Pages();

        $form = $this->createFormBuilder($pages)
            ->add("name", TextType::class, array("label"=>"頁面名稱"))
            ->add("alias", TextType::class, array("label"=>"頁面代稱"))
            ->add("content", TextareaType::class, array("label"=>"頁面內容"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pages = $form->getData();

            $currentTime = new \DateTime('now', new \DateTimeZone("Asia/Taipei"));
            $pages->setCreateTime($currentTime);
            $pages->setUpdateTime($currentTime);

            $em = $this->getDoctrine()->getManager();
            $em->persist($pages);
            $em->flush();

        }

        return $this->render("page-editor.html.twig", array(
            "form" => $form->createView()
        ));

    }

    public function edit(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Pages::class);
        $pages = $pageRepository->find($id);

        $form = $this->createFormBuilder($pages)
            ->add("name", TextType::class, array("label"=>"頁面名稱"))
            ->add("alias", TextType::class, array("label"=>"頁面代稱"))
            ->add("content", TextareaType::class, array("label"=>"頁面內容"))
            ->getForm();

        if ($form->isSubmitted() && $form->isValid()) {

            $pages = $form->getData();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $pages->setUpdateTime($currentTime);

            $em->persist($pages);
            $em->flush();

        }

        return $this->render("page-editor.html.twig", array(
            "form" => $form->createView()
        ));

    }

    public function delete(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $pagesRepository = $em->getRepository(Pages::class);
        $pages = $pagesRepository->find($id);

        $em->remove($pages);
        $em->flush();
        return $this->redirectToRoute("admin.pages.list");
    }

    public function listAll(Request $request, $page) {

    }
}
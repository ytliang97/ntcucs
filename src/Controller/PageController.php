<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/1/31
 * Time: 上午 09:19
 */

namespace App\Controller;


use App\Entity\Page;
use App\Form\Type\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function create(Request $request) {

        $page = new Page();

        $form = $this->createForm(PageType::class, $page);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $page = $form->getData();

            $currentTime = new \DateTime('now', new \DateTimeZone("Asia/Taipei"));
            $page->setCreateTime($currentTime);
            $page->setUpdateTime($currentTime);

            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute("admin.page.list");
        }

        return $this->render("admin/page-editor.html.twig", array(
            "form" => $form->createView(),
            "page" => $page
        ));

    }

    public function edit(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->find($id);

        $form = $this->createForm(PageType::class, $page);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $page = $form->getData();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $page->setUpdateTime($currentTime);

            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute("admin.page.list");

        }

        return $this->render("admin/page-editor.html.twig", array(
            "form" => $form->createView(),
            "page" => $page
        ));

    }

    public function delete(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $pagesRepository = $em->getRepository(Page::class);
        $page = $pagesRepository->find($id);

        $em->remove($page);
        $em->flush();
        return $this->redirectToRoute("admin.page.list");
    }

    public function listAll(Request $request, $page) {

        $em = $this->getDoctrine()->getManager();

        $pagesRepository = $em->getRepository(Page::class);
        $page = $pagesRepository->findBy(array(), array("createTime"=>"DESC"));

        return $this->render("admin/pages-list.html.twig", array("pages"=>$page));
    }
}
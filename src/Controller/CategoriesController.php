<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/1/29
 * Time: 下午 09:21
 */

namespace App\Controller;

use App\Entity\Categories;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends Controller
{
    public function create(Request $request) {

        $categories = new Categories();

        $form = $this->createFormBuilder($categories)
            ->add("name", TextType::class, array("label"=>"類別名稱"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categories = $form->getData();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $categories->setCreateTime($currentTime);
            $categories->setUpdateTime($currentTime);

            $em = $this->getDoctrine()->getManager();
            $em->persist($categories);
            $em->flush();
        }

        return $this->render("category-editor.html.twig", array(
           "form" => $form->createView()
        ));

    }

    public function edit(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $categoriesRepository = $em->getRepository(Categories::class);
        $categories = $categoriesRepository->find($id);

        $form = $this->createFormBuilder($categories)
            ->add("name", TextType::class, array("label"=>"類別名稱"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categories = $form->getData();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $categories->setUpdateTime($currentTime);

            $em->persist($categories);
            $em->flush();
        }

        return $this->render("category-editor.html.twig", array(
            "form" => $form->createView()
        ));

    }

    public function delete(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $CategoriesRepository = $em->getRepository(Categories::class);
        $categories = $CategoriesRepository->find($id);

        $em->remove($categories);
        $em->flush();

        return $this->redirectToRoute("admin.categories.list");

    }

    public function listAll(Request $request, $page) {

    }
}
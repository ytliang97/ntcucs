<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/1/31
 * Time: 上午 09:19
 */

namespace App\Controller;


use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    public function create(Request $request) {

        $category = new Category();

        $form = $this->createFormBuilder($category)
            ->add("name", TextType::class, array("label"=>"類別名稱"))
            ->add("submit", SubmitType::class, array("label"=>"新增分類"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $category->setCreateTime($currentTime);
            $category->setUpdateTime($currentTime);

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute("admin.category.list");
        }

        return $this->render("admin/category-editor.html.twig", array(
            "form" => $form->createView()
        ));

    }

    public function edit(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $categoriesRepository = $em->getRepository(Category::class);
        $category = $categoriesRepository->find($id);

        $form = $this->createFormBuilder($category)
            ->add("name", TextType::class, array("label"=>"類別名稱"))
            ->add("submit", SubmitType::class, array("label"=>"更新分類"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $category->setUpdateTime($currentTime);

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute("admin.category.list");
        }

        return $this->render("admin/category-editor.html.twig", array(
            "form" => $form->createView()
        ));

    }

    public function delete(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $categoriesRepository = $em->getRepository(Category::class);
        $category = $categoriesRepository->find($id);

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute("admin.category.list");

    }

    public function listAll(Request $request, $page) {

        $em = $this->getDoctrine()->getManager();

        $categoriesRepository = $em->getRepository(Category::class);
        $categories = $categoriesRepository->findBy(array(), array("createTime"=>"DESC"));

        return $this->render("admin/categories-list.html.twig", array("categories"=>$categories));
    }
}
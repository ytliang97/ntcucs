<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/1/31
 * Time: 上午 09:19
 */

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Post;
use App\Exception\PageRequestOutOfRange;
use App\Repository\PostRepository;
use App\Services\PaginationService;
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
            ->add("alias", TextType::class, array("label" => "類別代稱"))
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
            ->add("alias", TextType::class, array("label" => "類別代稱"))
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

    public function show(Request $request, $alias) {

        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->getCategory($alias);
        if (!$category) {
            $category = $categoryRepository->getCategory("activities");
        }

        return $this->render("front/news.html.twig", array("category" => $category));
    }

    public function showActivities(Request $request, $page) {
        return $this->renderNewsList($request, "activities", $page);
    }

    public function showHiring(Request $request, $page) {
        return $this->renderNewsList($request, "hiring", $page);
    }

    public function showEnrollment(Request $request, $page) {
        return $this->renderNewsList($request, "enrollment", $page);
    }

    public function showScholarship(Request $request, $page) {
        return $this->renderNewsList($request, "scholarship", $page);
    }

    public function showOther(Request $request, $page) {
        return $this->renderNewsList($request, "other", $page);
    }

    public function showEnrollmentBachelor(Request $request, $page) {
        return $this->renderEnrollmentList($request, "enrollment-bachelor", $page);
    }

    public function showEnrollmentMaster(Request $request, $page) {
        return $this->renderEnrollmentList($request, "enrollment-master", $page);
    }

    public function showEnrollmentChina(Request $request, $page) {
        return $this->renderEnrollmentList($request, "enrollment-china", $page);
    }

    public function showEnrollmentInternational(Request $request, $page) {
        return $this->renderEnrollmentList($request, "enrollment-international", $page);
    }

    public function showEnrollmentFaq(Request $request, $page) {
        return $this->renderEnrollmentList($request, "enrollment-faq", $page);
    }

    public function renderNewsList(Request $request, $alias, $page) {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var Category $category
         */
        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->findOneBy(array("alias" => $alias));
        if ($category) {

            /**
             * @var PostRepository $postsRepository
             */
            $postsRepository = $em->getRepository(Post::class);
            $posts = $postsRepository->getPostInCategory($category->getId(), $page);

            /**
             * @var PaginationService $paginationService
             */
            $paginationService = $this->get('pagination');
            $pagination = null;
            try {
                $pagination = $paginationService->
                getPagination($page,
                    $postsRepository->countPostInCategory($alias));
            } catch (PageRequestOutOfRange $e) {
                return $this->redirectToRoute($request->get('_route'),
                    array(
                        "page" => $e->getMaxPage()
                    )
                );
            }

            return $this->render("front/news.html.twig", array(
                    "category" => $category,
                    "posts" => $posts,
                    "pagination" => $pagination,
                    "route" => $request->get('_route')
                )
            );
        }
        return $this->render("front/news.html.twig", array("category" => null)
        );
    }
    public function renderEnrollmentList(Request $request, $alias, $page) {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var Category $category
         */
        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->findOneBy(array("alias" => $alias));

        if ($category) {

            /**
             * @var PostRepository $postsRepository
             */
            $postsRepository = $em->getRepository(Post::class);
            $posts = $postsRepository->getPostInCategory($category->getId(), $page);

            /**
             * @var PaginationService $paginationService
             */
            $paginationService = $this->get('pagination');
            $pagination = null;
            try {
                $pagination = $paginationService->
                getPagination($page,
                    $postsRepository->countPostInCategory($alias));
            } catch (PageRequestOutOfRange $e) {
                return $this->redirectToRoute($request->get('_route'),
                    array(
                        "page" => $e->getMaxPage()
                    )
                );
            }

            return $this->render("front/enrollment.html.twig", array(
                    "category" => $category,
                    "posts" => $posts,
                    "pagination" => $pagination,
                    "route" => $request->get('_route')
                )
            );
        }
        return $this->render("front/enrollment.html.twig", array("category" => null)
        );
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/20
 * Time: 上午 10:26
 */

namespace App\Controller;


use App\Entity\Album;
use App\Entity\Banner;
use App\Entity\FileArchive;
use App\Entity\Page;
use App\Entity\Post;
use App\Exception\PageRequestOutOfRange;
use App\Repository\PostRepository;
use App\Services\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SiteController extends Controller
{
    public function index(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $postsRepository = $em->getRepository(Post::class);
        $bannerRepository = $em->getRepository(Banner::class);

        return $this->render("front/index.html.twig", array(
            "posts" => $postsRepository->getNewestPost(8),
            "banner" => $bannerRepository->findBy(array(), array("createTime"=>"DESC"))
        ));
    }

    public function course(Request $request) {
        return $this->render("front/course-bachelor.html.twig");
    }

    public function news(Request $request, $page) {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var PostRepository $postsRepository
         */
        $postsRepository = $em->getRepository(Post::class);


        $posts = $postsRepository->getAllNewsByPage($page);

        /**
         * @var PaginationService $paginationService
         */
        $paginationService = $this->get('pagination');
        $pagination = null;
        try {
            $pagination = $paginationService->
            getPagination($page,
                $postsRepository->countAllNews());
        } catch (PageRequestOutOfRange $e) {
            return $this->redirectToRoute("front.news",
                array(
                    "page" => $e->getMaxPage()
                )
            );
        }

        return $this->render("front/all-news-list.html.twig", array(
            "posts" => $posts,
            "pagination" => $pagination,
            "route" => 'front.news'
        ));
    }

    public function download(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $fileArchiveRepository = $em->getRepository(FileArchive::class);

        $fileArchives = array();

        $fileArchives["student-master-project"] = $fileArchiveRepository->findOneBy(array("alias"=>"student-master-project"));
        $fileArchives["student-bachelor-project"] = $fileArchiveRepository->findOneBy(array("alias"=>"student-bachelor-project"));
        $fileArchives["student-migration"] = $fileArchiveRepository->findOneBy(array("alias"=>"student-migration"));
        $fileArchives["student-master-course-architecture"] =
            $fileArchiveRepository->findOneBy(array("alias" => "student-master-course-architecture"));
        $fileArchives["student-bachelor-course-architecture"] =
            $fileArchiveRepository->findOneBy(array("alias" => "student-bachelor-course-architecture"));
        $fileArchives["student-scholarship"] =
            $fileArchiveRepository->findOneBy(array("alias" => "student-scholarship"));

        return $this->render("front/download.html.twig", array("fileArchives" => $fileArchives));
    }

    public function studentInfo(Request $request) {
        return $this->render("front/student.html.twig");
    }

    public function showAlbums(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $albumRepository = $em->getRepository(Album::class);
        $albums = $albumRepository->findBy(array(), array("createTime" => "DESC"));

        return $this->render("front/album.html.twig", array("albums" => $albums));
    }

    public function showAlbumPhotos(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $albumRepository = $em->getRepository(Album::class);
        $album = $albumRepository->find($id);

        return $this->render("front/album-show.html.twig", array("album" => $album));
    }

    public function bachelorEnrollmentRule(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"bachelor-enrollment-rule"));

        return $this->render("front/bachelor-enrollment-rule.html.twig", array("page"=>$page));
    }

    public function bachelorCourseData(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $fileArchiveRepository = $em->getRepository(FileArchive::class);

        $archive = $fileArchiveRepository->findOneBy(array("alias"=>"bachelor-course-data"));

        return $this->render("front/bachelor-course-file-table.html.twig", array("archive" => $archive));
    }

    public function bachelorAdmitList(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $fileArchiveRepository = $em->getRepository(FileArchive::class);

        $archive = $fileArchiveRepository->findOneBy(array("alias"=>"bachelor-admit-list"));

        return $this->render("front/bachelor-course-file-table.html.twig", array("archive" => $archive));
    }

    public function masterEnrollmentRule(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"master-enrollment-rule"));

        return $this->render("front/master-enrollment-rule.html.twig", array("page"=>$page));
    }

    public function masterCourseData(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $fileArchiveRepository = $em->getRepository(FileArchive::class);

        $archive = $fileArchiveRepository->findOneBy(array("alias"=>"master-course-data"));

        return $this->render("front/master-course-file-table.html.twig", array("archive" => $archive));
    }
}
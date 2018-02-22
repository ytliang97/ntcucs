<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/20
 * Time: 上午 10:26
 */

namespace App\Controller;


use App\Entity\FileArchive;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SiteController extends Controller
{
    public function index(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $postsRepository = $em->getRepository(Post::class);

        return $this->render("front/index.html.twig", array(
            "posts" => $postsRepository->getNewestPost(5)
        ));
    }

    public function course(Request $request) {
        return $this->render("front/course.html.twig");
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


}
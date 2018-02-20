<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/20
 * Time: 上午 10:26
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        return $this->render("front/index.html.twig");
    }
    public function news(Request $request) {
        return $this->render("front/news.html.twig");
    }
    public function introduce(Request $request) {
        return $this->render("front/introduce.html.twig");
    }

    public function course(Request $request) {
        return $this->render("front/course.html.twig");
    }

    public function enrollment(Request $request) {
        return $this->render("front/enrollment.html.twig");
    }

    public function download(Request $request) {
        return $this->render("front/download.html.twig");
    }

    public function studentInfo(Request $request) {
        return $this->render("front/student.html.twig");
    }

    public function networkResource(Request $request) {
        return $this->render("front/network-resource.html.twig");
    }
}
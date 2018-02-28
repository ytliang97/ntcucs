<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/19
 * Time: 下午 01:50
 */

namespace App\Controller;


use App\Entity\Banner;
use App\Entity\PublicUploaded;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class BannerController extends Controller
{
    public function add(Request $request) {

        $banner = new Banner();

        $form = $this->createFormBuilder($banner)
            ->add("title", TextType::class, array("label" => "Banner 標題"))
            ->add("subtitle", TextType::class, array("label" => "Banner 副標題", "required"=>false))
            ->add("linkto", TextType::class, array("label" => "Banner 連結", "required"=>false))
            ->add("image", FileType::class,
                array(
                    "label" => "Banner 圖片",
                    "required" => false,
                    "mapped" => false
                )
            )
            ->add("submit", SubmitType::class, array("label" => "新增Banner"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $banner = $form->getData();

            $image = $form["image"]->getData();

            $fileOriginName = $image->getClientOriginalName();
            $fileType = $image->getClientMimeType();
            $fileExt = $image->getClientOriginalExtension();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $fileHashName = md5($fileOriginName).md5($currentTime->format("Y-m-d H:i:s"));
            $fileHashName .= ".".$fileExt;

            $targetDirectory = $this->container->getParameter("public_directory");
            $subdir = "";

            if (preg_match("/image\/\w+/", $fileType)) {
                $subdir = "/images";
            }
            else if (preg_match("/video\/\w+/", $fileType)) {
                $subdir = "/videos";
            }
            else {
                $subdir = "/others";
            }
            $targetDirectory .= $subdir;
            $fullPath = $subdir."/".$fileHashName;

            $banner->setCreateTime($currentTime);

            $image->move($targetDirectory, $fileHashName);

            $publicUploaded = new PublicUploaded();
            $publicUploaded->setHashName($fileHashName);
            $publicUploaded->setFileType($fileType);
            $publicUploaded->setCreateTime($currentTime);
            $publicUploaded->setFullPath($fullPath);
            $banner->setImage($publicUploaded);

            $em = $this->getDoctrine()->getManager();
            $em->persist($publicUploaded);
            $em->persist($banner);
            $em->flush();

            return $this->redirectToRoute("admin.banner.list");

        }

        return $this->render("admin/banner-editor.html.twig", array("form" => $form->createView()));

    }

    public function edit(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $bannerRepository = $em->getRepository(Banner::class);
        $banner = $bannerRepository->find($id);

        $form = $this->createFormBuilder($banner)
            ->add("title", TextType::class, array("label" => "Banner 標題"))
            ->add("subtitle", TextType::class, array("label" => "Banner 副標題", "required"=>false))
            ->add("linkto", TextType::class, array("label" => "Banner 連結", "required"=>false))
            ->add("image", FileType::class,
                array(
                    "label" => "Banner 圖片",
                    "required" => false,
                    "mapped" => false
                )
            )
            ->add("submit", SubmitType::class, array("label" => "新增Banner"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $banner = $form->getData();

            if ($form["image"] != null) {

                // delete origin
                $originImage = $banner->getImage();
                $targetDirectory = $this->container->getParameter("public_directory");
                unlink($targetDirectory.$originImage->getFullPath());

                $em->remove($originImage);
                $em->flush();

                // renew data

                $image = $form["image"]->getData();

                $fileOriginName = $image->getClientOriginalName();
                $fileType = $image->getClientMimeType();
                $fileExt = $image->getClientOriginalExtension();

                $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
                $fileHashName = md5($fileOriginName) . md5($currentTime->format("Y-m-d H:i:s"));
                $fileHashName .= "." . $fileExt;

                $targetDirectory = $this->container->getParameter("public_directory");
                $subdir = "";

                if (preg_match("/image\/\w+/", $fileType)) {
                    $subdir = "/images";
                } else if (preg_match("/video\/\w+/", $fileType)) {
                    $subdir = "/videos";
                } else {
                    $subdir = "/others";
                }
                $targetDirectory .= $subdir;
                $fullPath = $subdir . "/" . $fileHashName;

                $image->move($targetDirectory, $fileHashName);

                $publicUploaded = new PublicUploaded();
                $publicUploaded->setHashName($fileHashName);
                $publicUploaded->setFileType($fileType);
                $publicUploaded->setCreateTime($currentTime);
                $publicUploaded->setFullPath($fullPath);
                $banner->setImage($publicUploaded);
                $em->persist($publicUploaded);
            }

            $em->persist($banner);
            $em->flush();

            return $this->redirectToRoute("admin.banner.list");

        }

        return $this->render("admin/banner-editor.html.twig", array("form" => $form->createView()));
    }

    public function delete(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $bannerRepository = $em->getRepository(Banner::class);
        $banner = $bannerRepository->find($id);

        $image = $banner->getImage();

        if ($image) {
            $em->remove($image);
        }
        $em->remove($banner);
        $em->flush();

        return $this->redirectToRoute("admin.banner.list");
    }

    public function listAll(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $bannerRepository = $em->getRepository(Banner::class);
        $banners = $bannerRepository->findAll();

        return $this->render("admin/banner-list.html.twig", array("banners" => $banners));
    }
}
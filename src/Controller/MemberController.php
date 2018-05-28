<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/15
 * Time: 上午 10:09
 */

namespace App\Controller;

use App\Entity\Member;
use App\Entity\PublicUploaded;
use App\Entity\Team;
use App\Repository\MemberRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class MemberController extends Controller
{
    public function create(Request $request) {

        $member = new Member();

        $em = $this->getDoctrine()->getManager();
        $memberRepository = $em->getRepository(Member::class);
        $allMembers = $memberRepository->findAll();

        $allMembersAmount = sizeof($allMembers);

        $form = $this->createFormBuilder($member)
            ->add("name", TextType::class, array("label" => "姓名"))
            ->add("title", TextType::class, array("label" => "職稱"))
            ->add("officeNumber", TextType::class, array("label" => "辦公室電話", "required" => false))
            ->add("cellPhone", TextType::class, array("label" => "手機號碼", "required" => false))
            ->add("email", EmailType::class, array("label" => "電子郵件", "required" => false))
            ->add("website", TextType::class, array("label" => "網站", "required" => false))
            ->add("major", TextareaType::class, array("label" => "專業領域", "required" => false))
            ->add("team", EntityType::class, array(
                    "required" => false,
                    "label" => "所屬團隊",
                    "class" => Team::class,
                    "choice_label" => "name"
                )
            )
            ->add('avatar', FileType::class, array(
                    "label" => "個人相片",
                    "required"=>false,
                    "mapped" => false,
                )
            )
            ->add('memberOrder', IntegerType::class, array("label" => "排列", "data" => $allMembersAmount + 1))
            ->add("submit", SubmitType::class, array("label" => "新增成員"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $member = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $member->setCreateTime($currentTime);
            $member->setUpdateTime($currentTime);

            $avatar = $form["avatar"]->getData();

            if ($avatar) {
                $fileOriginName = $avatar->getClientOriginalName();
                $fileType = $avatar->getClientMimeType();
                $fileExt = $avatar->getClientOriginalExtension();

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

                $member->setCreateTime($currentTime);

                $avatar->move($targetDirectory, $fileHashName);

                $publicUploaded = new PublicUploaded();
                $publicUploaded->setHashName($fileHashName);
                $publicUploaded->setFileType($fileType);
                $publicUploaded->setCreateTime($currentTime);
                $publicUploaded->setFullPath($fullPath);

                $em->persist($publicUploaded);
                $member->setAvatar($publicUploaded);
            }
            $em->persist($member);
            $em->flush();

            return $this->redirectToRoute("admin.member.list");

        }

        return $this->render("admin/member-create.html.twig", array("form" => $form->createView()));

    }

    public function edit(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $memberRepository = $em->getRepository(Member::class);
        $member = $memberRepository->find($id);

        $form = $this->createFormBuilder($member)
            ->add("name", TextType::class, array("label" => "姓名"))
            ->add("title", TextType::class, array("label" => "職稱"))
            ->add("officeNumber", TextType::class, array("label" => "辦公室電話", "required" => false))
            ->add("cellPhone", TextType::class, array("label" => "手機號碼", "required" => false))
            ->add("email", EmailType::class, array("label" => "電子郵件", "required" => false))
            ->add("website", TextType::class, array("label" => "網站", "required" => false))
            ->add("major", TextareaType::class, array("label" => "專業領域", "required" => false))
            ->add("team", EntityType::class, array(
                    "required" => false,
                    "label" => "所屬團隊",
                    "class" => Team::class,
                    "choice_label" => "name"
                )
            )
            ->add('avatar', FileType::class, array(
                    "label" => "個人相片",
                    "required"=>false,
                    "mapped" => false
                )
            )
            ->add('memberOrder', IntegerType::class, array("label" => "排列"))
            ->add("submit", SubmitType::class, array("label" => "編輯成員"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $member = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $member->setUpdateTime($currentTime);

            $avatar = $form["avatar"]->getData();
            if ($avatar) {

                $fileOriginName = $avatar->getClientOriginalName();
                $fileType = $avatar->getClientMimeType();
                $fileExt = $avatar->getClientOriginalExtension();

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

                $member->setCreateTime($currentTime);

                $avatar->move($targetDirectory, $fileHashName);

                if ($member->getAvatar()) {
                    unlink($this->container->getParameter("public_directory")."/".$member->getAvatar()->getFullPath());
                    $em->remove($member->getAvatar());
                }

                $publicUploaded = new PublicUploaded();
                $publicUploaded->setHashName($fileHashName);
                $publicUploaded->setFileType($fileType);
                $publicUploaded->setCreateTime($currentTime);
                $publicUploaded->setFullPath($fullPath);

                $em->persist($publicUploaded);
                $member->setAvatar($publicUploaded);
            }
            $em->persist($member);
            $em->flush();

            return $this->redirectToRoute("admin.member.list");

        }

        return $this->render("admin/member-create.html.twig", array("form" => $form->createView()));

    }

    public function delete(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $memberRepository = $em->getRepository(Member::class);
        $member = $memberRepository->find($id);

        $em->remove($member);
        $em->flush();

        return $this->redirectToRoute("admin.member.list");
    }

    public function listAll(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $memberRepository = $em->getRepository(Member::class);
        $members = $memberRepository->findAll();
        return $this->render("admin/member-list.html.twig", array("members" => $members));

    }
}
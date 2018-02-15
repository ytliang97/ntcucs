<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/15
 * Time: 上午 10:09
 */

namespace App\Controller;

use App\Entity\Member;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class MemberController extends Controller
{
    public function create(Request $request) {

        $member = new Member();

        $form = $this->createFormBuilder($member)
            ->add("name", TextType::class, array("label" => "姓名"))
            ->add("title", TextType::class, array("label" => "職稱"))
            ->add("officeNumber", TextType::class, array("label" => "辦公室電話", "required" => false))
            ->add("cellPhone", TextType::class, array("label" => "手機號碼", "required" => false))
            ->add("email", EmailType::class, array("label" => "電子郵件", "required" => false))
            ->add("website", TextType::class, array("label" => "網站", "required" => false))
            ->add("team", EntityType::class, array(
                    "required" => false,
                    "label" => "所屬團隊",
                    "class" => Team::class,
                    "choice_label" => "name"
                )
            )
            ->add("submit", SubmitType::class, array("label" => "新增成員"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $member = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $member->setCreateTime($currentTime);
            $member->setUpdateTime($currentTime);

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
            ->add("team", EntityType::class, array(
                    "required" => false,
                    "label" => "所屬團隊",
                    "class" => Team::class,
                    "choice_label" => "name"
                )
            )
            ->add("submit", SubmitType::class, array("label" => "新增成員"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $member = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $member->setUpdateTime($currentTime);

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
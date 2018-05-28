<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/1/31
 * Time: 上午 09:19
 */

namespace App\Controller;


use App\Entity\Member;
use App\Entity\Page;
use App\Entity\Team;
use App\Form\Type\PageType;
use App\Repository\MemberRepository;
use App\Repository\TeamRepository;
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

    public function showHistory(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"history"));
        return $this->render("front/introduce.html.twig", array("page"=>$page));

    }

    public function showMember(Request $request) {

        $em = $this->getDoctrine()->getManager();

        /**
         * @var TeamRepository $teamRepository
         */
        $teamRepository = $em->getRepository(Team::class);
        /**
         * @var Team $team
         * @var Team $adjunctTeacherTeam
         */
        $team = $teamRepository->findOneBy(array('alias' => 'department-office'));
        $adjunctTeacherTeam = $teamRepository->findOneBy(array('alias' => 'adjunct-teacher'));
        /**
         * @var MemberRepository $memberRepository
         */
        $memberRepository = $em->getRepository(Member::class);
        $members = null;
        if ($team) {
            $members = $memberRepository->findAllMemberWithoutTeam(array($team->getId(), $adjunctTeacherTeam->getId()));
        }
        else {
            $members = $memberRepository->findBy(array(), array('memberOrder' => 'ASC'));
        }

        return $this->render("front/introduce-member.html.twig", array("members" => $members));


    }

    public function showHonor(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"honor"));

        return $this->render("front/introduce.html.twig", array("page"=>$page));
    }

    public function showLab(Request $request, $labname) {

        $em = $this->getDoctrine()->getManager();
        $teamRepository = $em->getRepository(Team::class);
        $team = $teamRepository->findOneBy(array("alias"=>"lab-".$labname));
        $membersRepository = $em->getRepository(Member::class);
        $members = $membersRepository->findBy(array("team"=>$team), array("memberOrder"=>"ASC"));
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"lab-".$labname));

        return $this->render("front/introduce-lab.html.twig", array("page"=>$page, "members"=>$members));

    }

    public function showFuture(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"future"));

        return $this->render("front/introduce.html.twig", array("page"=>$page));
    }

    public function showLocation(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"location"));

        return $this->render("front/introduce.html.twig", array("page"=>$page));
    }

    public function showNetworkResource(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"network-resource"));

        return $this->render("front/network-resource.html.twig", array("page"=>$page));
    }

    public function showContactUs(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"contact-us"));

        return $this->render("front/introduce.html.twig", array("page"=>$page));
    }

    public function bachelorCourseAttention(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"bachelor-course-attention"));

        return $this->render("front/course-bachelor.html.twig", array("page"=>$page));

    }

    public function bachelorCourseArchitecture(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"bachelor-course-architecture"));

        return $this->render("front/course-bachelor.html.twig", array("page"=>$page));

    }

    public function bachelorGraduateCondition(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"bachelor-graduate-condition"));

        return $this->render("front/course-bachelor.html.twig", array("page"=>$page));
    }

    public function bachelorCourseContent(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"bachelor-course-content"));

        return $this->render("front/course-bachelor.html.twig", array("page"=>$page));

    }

    public function bachelorCourseDescription(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"bachelor-course-description"));

        return $this->render("front/course-bachelor.html.twig", array("page"=>$page));

    }

    public function masterCourseAttention(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"master-course-attention"));

        return $this->render("front/course-master.html.twig", array("page"=>$page));

    }

    public function masterCourseArchitecture(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"master-course-architecture"));

        return $this->render("front/course-master.html.twig", array("page"=>$page));

    }

    public function masterGraduateCondition(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"master-graduate-condition"));

        return $this->render("front/course-master.html.twig", array("page"=>$page));

    }

    public function masterCourseContent(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"master-course-content"));

        return $this->render("front/course-master.html.twig", array("page"=>$page));
    }

    public function masterCourseDescription(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"master-course-description"));

        return $this->render("front/course-master.html.twig", array("page"=>$page));

    }

    public function showEducationAchievements(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"education-achievements"));

        return $this->render("front/introduce.html.twig", array("page"=>$page));
    }

    public function showCoreAbilities(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"core-abilities"));

        return $this->render("front/introduce.html.twig", array("page"=>$page));
    }

    public function showDualEducation(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"dual-education"));

        return $this->render("front/introduce.html.twig", array("page"=>$page));
    }

    public function showOfficer(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $teamRepository = $em->getRepository(Team::class);
        $team = $teamRepository->findOneBy(array("alias"=>"department-office"));
        $membersRepository = $em->getRepository(Member::class);
        $members = $membersRepository->findBy(array("team"=>$team), array("memberOrder"=>"ASC"));
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias"=>"department-office"));

        return $this->render("front/introduce-officer.html.twig", array("page"=>$page, "members"=>$members));
    }

    public function showEnrollmentBachelor(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias" => 'enrollment-bachelor'));

        return $this->render("front/enrollment.html.twig", array("page" => $page));
    }

    public function showEnrollmentMaster(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias" => 'enrollment-master'));

        return $this->render("front/enrollment.html.twig", array("page" => $page));
    }

    public function showEnrollmentChina(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias" => 'enrollment-china'));

        return $this->render("front/enrollment.html.twig", array("page" => $page));
    }

    public function showEnrollmentInternational(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias" => 'enrollment-international'));

        return $this->render("front/enrollment.html.twig", array("page" => $page));
    }

    public function showEnrollmentFaq(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias" => 'enrollment-faq'));

        return $this->render("front/enrollment.html.twig", array("page" => $page));
    }
    public function showStudentAssociationLeader(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias" => 'student-association-leader'));

        return $this->render("front/association.html.twig", array("page" => $page));
    }

    public function showGraduateAssociationLeader(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository(Page::class);
        $page = $pageRepository->findOneBy(array("alias" => 'student-association-leader'));

        return $this->render("front/association.html.twig", array("page" => $page));
    }
}
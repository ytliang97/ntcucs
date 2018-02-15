<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/15
 * Time: 上午 10:09
 */

namespace App\Controller;


use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class TeamController extends Controller
{
    public function create(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $team = new Team();

        $form = $this->createFormBuilder($team)
            ->add("name", TextType::class, array("label" => "團隊名稱"))
            ->add("description", TextareaType::class, array("label" => "團隊敘述", "required" => false))
            ->add("submit", SubmitType::class, array("label" => "新增團隊"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $team = $form->getData();

            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute("admin.team.list");

        }

        return $this->render("admin/team-editor.html.twig", array("form" => $form->createView()));

    }

    public function edit(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $teamRepository = $em->getRepository(Team::class);
        $team = $teamRepository->find($id);

        $form = $this->createFormBuilder($team)
            ->add("name", TextType::class, array("label" => "團隊名稱"))
            ->add("description", TextareaType::class, array("label" => "團隊敘述", "required" => false))
            ->add("submit", SubmitType::class, array("label" => "更新團隊"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $team = $form->getData();

            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute("admin.team.list");

        }

        return $this->render("admin/team-editor.html.twig", array("form" => $form->createView()));
    }

    public function delete(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $teamRepository = $em->getRepository(Team::class);
        $team = $teamRepository->find($id);

        $em->remove($team);
        $em->flush();
        return $this->redirectToRoute("admin.team.list");
    }

    public function listAll(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $teamRepository = $em->getRepository(Team::class);
        $teams = $teamRepository->findAll();

        return $this->render("admin/team-list.html.twig", array("teams" => $teams));
    }
}
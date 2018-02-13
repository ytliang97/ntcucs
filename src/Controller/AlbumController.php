<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/1
 * Time: 下午 11:31
 */

namespace App\Controller;


use App\Entity\Album;
use App\Entity\PublicUploaded;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class AlbumController extends Controller
{
    public function create(Request $request) {

        $album = new Album();

        $form = $this->createFormBuilder($album)
            ->add("name", TextType::class, array("label" => "相簿名稱"))
            ->add("description", TextareaType::class, array("label" => "相簿敘述"))
            ->add("submit", SubmitType::class, array("label" => "新增相簿"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $album = $form->getData();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $album->setCreateTime($currentTime);
            $album->setUpdateTime($currentTime);

            $em = $this->getDoctrine()->getManager();
            $em->persist($album);
            $em->flush();

            return $this->redirectToRoute("admin.album.edit", array("id"=>$album->getId()));

        }

        return $this->render("admin/album-add.html.twig",
                             array(
                                 "form" => $form->createView()
                             )
        );

    }

    public function edit(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $albumRepository = $em->getRepository(Album::class);
        $album = $albumRepository->find($id);

        $form = $this->createFormBuilder($album)
            ->add("name", TextType::class, array("label" => "相簿名稱"))
            ->add("description", TextareaType::class, array("label" => "相簿敘述"))
            ->add("existed",
                  TextType::class,
                  array(
                      "mapped" => false,
                  ))
            ->add("submit", SubmitType::class, array("label" => "更新相簿"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $album = $form->getData();

            $existed = json_decode($form["existed"]->getData());

            $publicUploadedRepository = $em->getRepository(PublicUploaded::class);
            $result = array();

            foreach ($existed as $file) {

                $photo = $publicUploadedRepository->find($file->id);
                if ($photo) {
                    array_push($result, $photo);
                }

            }

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $album->setUpdateTime($currentTime);
            $album->setContent($result);

            $em->persist($album);
            $em->flush();

            return $this->redirectToRoute("admin.album.list");

        }

        return $this->render("admin/album-editor.html.twig",
                             array("form" => $form->createView(),
                                   "album"=>$album));
    }

    public function listAll(Request $request, $page) {
        return $this->render("admin/album-list.html.twig");
    }

    public function delete(Request $request, $id) {

    }

    public function showAlbum(Request $request, $id) {

    }
}
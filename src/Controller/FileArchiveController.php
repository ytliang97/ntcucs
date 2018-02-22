<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/14
 * Time: 下午 12:34
 */

namespace App\Controller;


use App\Entity\File;
use App\Entity\FileArchive;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class FileArchiveController extends Controller
{
    public function create(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $fileArchive = new FileArchive();

        $form = $this->createFormBuilder($fileArchive)
            ->add("name", TextType::class, array("label" => "檔案庫名稱"))
            ->add("alias", TextType::class, array("label" => "檔案庫代稱"))
            ->add("description", TextareaType::class, array("label" => "檔案庫敘述", "required" => false))
            ->add("submit", SubmitType::class, array("label" => "新增檔案庫"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileArchive = $form->getData();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $fileArchive->setCreateTime($currentTime);
            $fileArchive->setUpdateTime($currentTime);

            $em->persist($fileArchive);
            $em->flush();

            return $this->redirectToRoute("admin.file.archive.list.file", array("id"=>$fileArchive->getId()));

        }

        return $this->render("admin/file-archive-create.html.twig",
                             array("form" => $form->createView()));
    }

    public function listFiles(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();

        $fileArchiveRepository = $em->getRepository(FileArchive::class);
        $fileArchive = $fileArchiveRepository->find($id);

        return $this->render("admin/file-archive-list-file.html.twig", array("archive"=>$fileArchive));
    }

    public function listAll(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $fileArchiveRepository = $em->getRepository(FileArchive::class);
        $fileArchive = $fileArchiveRepository->findBy(array(), array("createTime" => "DESC"));

        return $this->render("admin/file-archive-list.html.twig", array("archives"=>$fileArchive));
    }

    public function addFile(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $archiveRepository = $em->getRepository(FileArchive::class);
        $fileArchive = $archiveRepository->find($id);

        $data = array();
        $form = $this->createFormBuilder($data)
            ->add("title", TextType::class, array("label"=>"文件標題"))
            ->add("file", FileType::class, array("label" => "選擇文件"))
            ->add("submit", SubmitType::class, array("label" => "新增至檔案庫"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $uploaded = $data["file"];

            $fileOriginName = $uploaded->getClientOriginalName();
            $fileType = $uploaded->getClientMimeType();
            $fileExt = $uploaded->getClientOriginalExtension();

            $fileHashName = uniqid($fileExt."_", true);

            $file = new File();
            $file->setTitle($data["title"]);
            $file->setOriginName($fileOriginName);
            $file->setFileType($fileType);
            $file->setHashName($fileHashName);

            $fileArchive->addFile($file);

            $targetDirectory = $this->container->getParameter("storage_directory");

            $uploaded->move($targetDirectory, $fileHashName);


            $em->persist($file);
            $em->persist($fileArchive);
            $em->flush();

            return $this->redirectToRoute("admin.file.archive.list.file", array("id"=>$fileArchive->getId()));

        }

        return $this->render("admin/file-archive-add-file.html.twig", array("archive"=>$fileArchive, "form"=>$form->createView()));
    }

    public function edit(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $fileArchiveRepository = $em->getRepository(FileArchive::class);
        $fileArchive = $fileArchiveRepository->find($id);

        $form = $this->createFormBuilder($fileArchive)
            ->add("name", TextType::class, array("label" => "檔案庫名稱"))
            ->add("alias", TextType::class, array("label" => "檔案庫代稱"))
            ->add("description", TextareaType::class, array("label" => "檔案庫敘述", "required" => false))
            ->add("submit", SubmitType::class, array("label" => "新增檔案庫"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileArchive = $form->getData();

            $currentTime = new \DateTime("now", new \DateTimeZone("Asia/Taipei"));
            $fileArchive->setUpdateTime($currentTime);

            $em->persist($fileArchive);
            $em->flush();

            return $this->redirectToRoute("admin.file.archive.list.file", array("id"=>$fileArchive->getId()));

        }

        return $this->render("admin/file-archive-create.html.twig",
            array("form" => $form->createView()));
    }

    public function delete(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $fileArchiveRepository = $em->getRepository(FileArchive::class);
        $archive = $fileArchiveRepository->find($id);

        $em->remove($archive);
        $em->flush();

        return $this->redirectToRoute("admin.file.archive.listAll");
    }

    public function deleteFile(Request $request, $id, $fileId) {

        $em = $this->getDoctrine()->getManager();
        $fileRepository =$em->getRepository(File::class);
        $fileArchiveRepository = $em->getRepository(FileArchive::class);
        $file = $fileRepository->find($fileId);
        $fileArchive = $fileArchiveRepository->find($id);

        $fileHashName = $file->getHashName();

        $em->remove($file);
        $em->flush();

        $targetDirectory = $this->container->getParameter("storage_directory");
        unlink($targetDirectory.'/'.$fileHashName);

        return $this->redirectToRoute("admin.file.archive.list.file", array("id"=>$fileArchive->getId()));

    }
}
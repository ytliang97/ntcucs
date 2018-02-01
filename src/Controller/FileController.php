<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/1/31
 * Time: 上午 09:16
 */

namespace App\Controller;


use App\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileController extends Controller
{
    public function getFile(Request $request, $id) {

        $action = $request->query->get("action");

        $em = $this->getDoctrine()->getManager();
        $fileRepository = $em->getRepository(File::class);
        $download = $fileRepository->find($id);

        $targetDirectory = $this->container->getParameter("storage_directory");

        $fileFullName = $targetDirectory."/".$download->getHashName();

        if ($action == "view") {
            return $this->file($fileFullName,
                               $download->getOriginName(),
                               ResponseHeaderBag::DISPOSITION_INLINE);
        }
        return $this->file($fileFullName, $download->getOriginName());

    }

    public function upload(Request $request) {

        $uploaded = $request->files->get("upload");
        if (!$uploaded) return $this->json(array("errString"=>"Request Invalid"), 400);

        $fileOriginName = $uploaded->getClientOriginalName();
        $fileType = $uploaded->getClientMimeType();
        $fileExt = $uploaded->getClientOriginalExtension();

        $fileHashName = uniqid($fileExt."_", true);

        $file = new File();
        $file->setOriginName($fileOriginName);
        $file->setFileType($fileType);
        $file->setHashName($fileHashName);

        $targetDirectory = $this->container->getParameter("storage_directory");

        $uploaded->move($targetDirectory, $fileHashName);

        $em = $this->getDoctrine()->getManager();
        $em->persist($file);
        $em->flush();

        $fileId = $file->getId();

        return $this->json(array("id"=>$fileId, "filename"=>$fileOriginName), 200);
    }

    public function delete(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $fileRepository =$em->getRepository(File::class);
        $file = $fileRepository->find($id);

        $fileHashName = $file->getHashName();

        $em->remove($file);
        $em->flush();

        $targetDirectory = $this->container->getParameter("storage_directory");
        unlink($targetDirectory.'/'.$fileHashName);

        return $this->json(array(), 200);

    }
}
<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/1
 * Time: 下午 08:45
 */

namespace App\Controller;


use App\Entity\PublicUploaded;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PublicUploadController extends Controller
{
    public function upload(Request $request) {

        $uploaded = $request->files->get("upload");
        if (!$uploaded) return $this->json(array("errString"=>"Request Invalid"), 400);

        $fileOriginName = $uploaded->getClientOriginalName();
        $fileType = $uploaded->getClientMimeType();
        $fileExt = $uploaded->getClientOriginalExtension();

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

        $uploaded->move($targetDirectory, $fileHashName);

        $publicUploaded = new PublicUploaded();
        $publicUploaded->setHashName($fileHashName);
        $publicUploaded->setFileType($fileType);
        $publicUploaded->setCreateTime($currentTime);
        $publicUploaded->setFullPath($fullPath);

        $em = $this->getDoctrine()->getManager();
        $em->persist($publicUploaded);
        $em->flush();

        return $this->json(array("id" => $publicUploaded->getId(),
                                 "filename" => $fullPath), 200);

    }

    public function delete(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $publicUploadedRepository = $em->getRepository(PublicUploaded::class);
        $file = $publicUploadedRepository->find($id);

        $targetDirectory = $this->container->getParameter("public_directory");

        unlink($targetDirectory.$file->getFullPath());

        $em->remove($file);
        $em->flush();

        return $this->json(array(), 200);

    }
}
<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/5
 * Time: 下午 02:56
 */

namespace App\Form\Datatransformer;

use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class AttachmentsToJsonTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function transform($value)
    {
    }

    public function reverseTransform($value)
    {
        $attachments = json_decode($value);


        $attachmentsSize = sizeof($attachments);
        $repository = $this->em->getRepository(File::class);
        $result = array();
        for($i = 0; $i < $attachmentsSize; $i ++) {
            $fileId = $attachments[$i]->id;
            $file = $repository->find($fileId);
            array_push($result, $file);
        }
        return $result;
    }
}
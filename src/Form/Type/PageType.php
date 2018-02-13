<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/13
 * Time: 下午 07:31
 */

namespace App\Form\Type;

use App\Entity\Page;
use App\Form\Datatransformer\AttachmentsToJsonTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    private $transformer;

    public function __construct(AttachmentsToJsonTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, array("label" => "頁面標題"))
            ->add("alias", TextType::class, array("label" => "頁面代稱"))
            ->add("content", TextareaType::class, array("label" => "頁面內容"))
            ->add("attachments", TextType::class, array("required"=>false))
            ->add("submit", SubmitType::class, array("label" => "送出頁面"));

        $builder->get("attachments")
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => Page::class
        ));
    }
}
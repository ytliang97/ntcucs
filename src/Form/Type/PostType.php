<?php
/**
 * Created by PhpStorm.
 * User: FloatFlower.Huang
 * Date: 2018/2/5
 * Time: 下午 02:41
 */

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\Datatransformer\AttachmentsToJsonTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    private $transformer;

    public function __construct(AttachmentsToJsonTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, array("label" => "文章標題"))
            ->add("content", TextareaType::class, array("label" => "文章內容"))
            ->add("categories",
                  EntityType::class,
                  array(
                      "class" => Category::class,
                      "label" => "文章分類",
                      "choice_label" => "name",
                      "multiple" => true,
                      "required" => false
                  )
            )
            ->add("attachments", TextType::class, array("required"=>false))
            ->add("submit", SubmitType::class, array("label" => "發表文章"));

        $builder->get("attachments")
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => Post::class
        ));
    }
}
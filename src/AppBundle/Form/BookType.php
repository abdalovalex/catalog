<?php
/**
 * Created by PhpStorm.
 * User: AbdalovAR
 * Date: 09.06.2018
 * Time: 22:09
 */

namespace AppBundle\Form;


use AppBundle\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
                ->add('publish', IntegerType::class, ['attr' => ['max' => date('Y')]])
                ->add('isbn', TextType::class)
                ->add('page', IntegerType::class)
                ->add('cover', FileType::class, ['data_class' => null, 'required' => false])
                ->add('authors', EntityType::class, ['class' => 'AppBundle:Author', 'choice_label' => 'fio', 'multiple' => true])
                ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Book::class]);
    }
}
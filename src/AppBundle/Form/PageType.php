<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('title', null, array(
                'label'=>"Tytuł"
            ))
            ->add('shortName', null, array(
                'label'=>"Skrócona nazwa"
            ))
            ->add('body',CKEditorType::class,array(
                'label'=>"Treść strony",
                'config' => array(
                    'uiColor' => '#ffffff',
                    //...
                ),
            ))
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle:Category',
                'choice_label' => 'title',
                'label'=> 'Kategoria'
                ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Page'
        ));
    }
}

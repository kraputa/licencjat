<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class PuzzleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'label'=> "Tytuł"
            ))
            ->add('body',CKEditorType::class,array(
                'label'=>'Ciekawostka',
                'config' => array(
                    'uiColor' => '#ffffff',
                    //...
                ),
            ))
            ->add('unlockQuestion',null, array(
                'label'=>"Zagadka",
                'attr'=>array('class'=> 'edit_page')
            ))
            ->add('unlockAnswer',null, array(
        'label'=> "Poprawna odpowiedź"
    ))
            ->add('questionPicture', EntityType::class, array(
                'class' => 'AppBundle:Picture',
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Ilustracja'
            ))
            ->add('pages', EntityType::class, array(
                'class' => 'AppBundle:Page',
                'choice_label' => 'title',
                'multiple'=>true,
                'required' => false,
                'label'=>"Powiązane strony"
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Puzzle'
        ));
    }
}

<?php


namespace App\Form;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ActivityBlockeeType extends ActivityType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('blockee', ChoiceType::class, ['choices' =>
                $this->getChoices()
            ])
            ->add('blocker')
        ;
    }


}
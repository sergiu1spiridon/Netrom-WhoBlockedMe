<?php


namespace App\Form;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ActivityBlockeeType extends ActivityType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (sizeof($this->getChoices()) == 1) {
            $licencePlate = $this->licencePlateService->findLicencePlatesByUserId()[0];
            $builder
                ->add('blockee',TextType::class, [
                    'required'   => false,
                    'empty_data' => $licencePlate,
                    'attr' => array(
                        'placeholder' => $licencePlate
                    )
                ])
                ->add('blocker');
        } else {
            $builder
                ->add('blockee', ChoiceType::class, ['choices' =>
                    $this->getChoices()
                ])
                ->add('blocker')
            ;
        }
    }


}
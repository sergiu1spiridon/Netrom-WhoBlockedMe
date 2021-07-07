<?php


namespace App\Form;


use App\Entity\Activity;
use App\Entity\LicencePlate;
use App\Repository\LicencePlateRepository;
use App\Service\LicencePlateService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType  extends AbstractType
{

    protected LicencePlateService $licencePlateService;

    /**
     * ActivityType constructor.
     * @param $licencePlateService
     */
    public function __construct(LicencePlateService $licencePlateService)
    {
        $this->licencePlateService = $licencePlateService;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('blocker', ChoiceType::class, ['choices' =>
            $this->getChoices()
            ])
            ->add('blockee')
//            ->add('status')
        ;
    }

    protected function getChoices()
    {
        $contents = $this->licencePlateService->findLicencePlatesByUserId();

        $result = [];
        foreach ($contents as $content) {
            $result[$content] = $content;
        }

        return $result;
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}

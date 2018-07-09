<?php
namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AdminBundle\Entity\Hire;

/**
 * Class ReturnHireType
 * @package AdminBundle\Form
 */
class ReturnHireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hireId', HiddenType::class, ['mapped' => false, 'data' => $builder->getData()->getId()])
            ->add('returnDate', DateType::class, [
                'html5' => true,
                'format' => DateType::HTML5_FORMAT,
                'label' => 'Data zwrotu',
                'data' => new \DateTime(),
                'widget' => 'single_text'
            ])
            ->add('submit', SubmitType::class, ['label' => 'Zapisz']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Hire::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_hire';
    }
}

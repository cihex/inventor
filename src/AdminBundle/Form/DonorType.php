<?php
namespace AdminBundle\Form;

use AdminBundle\Entity\Donor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DonorType
 * @package AdminBundle\Form
 */
class DonorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('donorId', HiddenType::class, ['data' => $builder->getData()->getId(), 'mapped' => false])
            ->add('name', null, ['label' => 'Imię i nazwisko*'])
            ->add('email', null, ['label' => 'Adres e-mail'])
            ->add('phone', null, ['label' => 'Numer telefonu'])
            ->add('street', null, ['label' => 'Ulica i numer domu'])
            ->add('city', null, ['label' => 'Miasto'])
            ->add('postalCode', null, ['label' => 'Kod pocztowy'])
            ->add('submit', SubmitType::class, ['label' => 'Zapisz']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Donor::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_donor';
    }


}

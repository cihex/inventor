<?php

namespace AdminBundle\Form;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Imię'])
            ->add('surname', null, ['label' => 'Nazwisko'])
            ->add('street', null, ['label' => 'Ulica i numer domu'])
            ->add('city', null, ['label' => 'Miasto'])
            ->add('postalCode', null, ['label' => 'Kod pocztowy'])
            ->add('phone', null, ['label' => 'Numer telefonu'])
            ->add('email', null, ['label' => 'Adres e-mail'])
            ->add('hireDate', DateType::class, [
                'html5' => true,
                'format' => DateType::HTML5_FORMAT,
                'label' => 'Data wypożyczenia',
                'data' => $builder->getData()->getHireDate() !== null
                    ? $builder->getData()->getHireDate()
                    : new \DateTime(),
                'widget' => 'single_text'
            ])
            ->add('plannedReturnDate', DateType::class, [
                'html5' => true,
                'format' => DateType::HTML5_FORMAT,
                'label' => 'Planowana data zwrotu',
                'data' => $builder->getData()->getHireDate() !== null
                    ? $builder->getData()->getPlannedReturnDate()
                    : (new \DateTime())->add(new \DateInterval('P10D')),
                'widget' => 'single_text'
            ])
            ->add('comment', null, ['label' => 'Kod pocztowy'])
            ->add('exhibits', null, ['label' => 'Eksponaty'])
            ->add('submit', SubmitType::class, ['label' => 'Zapisz']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AdminBundle\Entity\Hire'
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

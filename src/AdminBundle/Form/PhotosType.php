<?php

namespace AdminBundle\Form;

use AdminBundle\Entity\Category;
use AdminBundle\Entity\Donor;
use AdminBundle\Entity\ExhibitOwner;
use AdminBundle\Entity\ExhibitState;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AdminBundle\Entity\Exhibit;

/**
 * Class ExhibitType
 * @package AdminBundle\Form
 */
class PhotosType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add(
                'files',
                FileType::class,
                [
                    'label' => 'Zdjęcia',
                    'multiple' => true
                ]
            )
            ->add(
                'entityId',
                HiddenType::class,
                [
                    'data' => $options['entityId']
                ]
            )
            ->add('save', SubmitType::class, [
                'label' => 'Zapisz zmiany',
                'block_name' => 'Zapisz zmiany'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Exhibit::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_exhibit';
    }
}

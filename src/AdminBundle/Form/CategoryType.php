<?php

namespace AdminBundle\Form;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AdminBundle\Entity\Category;

/**
 * Class CategoryType
 * @package AdminBundle\Form
 */
class CategoryType extends AbstractType
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('categoryId', HiddenType::class, ['mapped' => false, 'data' => $builder->getData()->getId()])
            ->add('name', null, ['label' => 'Nazwa'])
            ->add('alias', null, ['label' => 'Skrót'])
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
            'data_class' => Category::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_category';
    }


}

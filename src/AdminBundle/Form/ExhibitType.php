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
class ExhibitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Nazwa',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Opis'
                ]
            )
            ->add(
                'producer',
                null,
                [
                    'label' => 'Producent'
                ]
            )
            ->add(
                'produceYear',
                null,
                [
                    'label' => 'Rok produkcji',
                ]
            )
            ->add(
                'adoptionDate',
                DateType::class,
                [
                    'label' => 'Data przyjęcia',
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'html5' => true
                ]
            )
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Kategoria',
                'multiple' => false,
            ])
            ->add('donor', EntityType::class, [
                'class' => Donor::class,
                'label' => 'Ofiarodawca'
            ])
            ->add('newDonorButton', ButtonType::class, [
                'label' => '+ Dodaj nowego',
                'block_name' => 'Dodaj nowego',
                'attr' => [
                    'class' => 'btn btn-success',
                    'onclick' => 'showForm(\'donor\')'
                ]
            ])
            ->add('state', EntityType::class, [
                'class' => ExhibitState::class,
                'label' => 'Stan'
            ])
            ->add('owner', EntityType::class, [
                'class' => ExhibitOwner::class,
                'label' => 'Właściciel'
            ])
            ->add('newOwnerButton', ButtonType::class, [
                'label' => '+ Dodaj nowego',
                'block_name' => 'Dodaj nowego',
                'attr' => [
                    'class' => 'btn btn-success',
                    'onclick' => 'showForm(\'owner\')'
                ]
            ])
            ->add('newCategoryButton', ButtonType::class, [
                'label' => '+ Dodaj nową',
                'block_name' => 'Dodaj nową',
                'attr' => [
                    'class' => 'btn btn-success',
                    'onclick' => 'showForm(\'category\')'
                ]
            ])
            ->add('photosId', HiddenType::class, [
                'mapped' => false,
                'data' => $this->getPhotosIdString($builder->getData())
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Zapisz zmiany',
                'block_name' => 'Zapisz zmiany'
            ]);
    }

    /**
     * @param Exhibit $exhibit
     * @return string
     */
    public function getPhotosIdString(Exhibit $exhibit)
    {
        $ids = [];
        foreach ($exhibit->getPhotos()->getIterator() as $photo) {
            $ids[] = $photo->getId();
        }
        return implode(',', $ids);
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

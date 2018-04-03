<?php

namespace AppBundle\Form;

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'label'=> 'Prénom '
                ])
            ->add('firstName', TextType::class, [
                'label' => 'Nom '
            ])
            ->add('birthDate', DateType::class, [
                'label' => 'Date de naissance ',
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y'), date('Y') - 100 ) //mettre constante à la place de 100
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays'
            ])
            ->add('reduceRate', CheckboxType::class, [
                'label' => 'Tarif réduit',
                'required' => false
            ]);


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Ticket::class
        ));
    }



}

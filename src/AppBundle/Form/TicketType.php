<?php

namespace AppBundle\Form;

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    const YEARS_IN_PAST = 100;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'label'=> 'ticket.firstname'
                ])
            ->add('firstName', TextType::class, [
                'label' => 'ticket.lastname'
            ])
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'ticket.birth_date',
                'years' => range(date('Y'), date('Y') - self::YEARS_IN_PAST)
            ])
            ->add('country', CountryType::class, [
                'label' => 'ticket.country',
                'preferred_choices'=>[
                    'FR'
                ]
            ])
            ->add('reduceRate', CheckboxType::class, [
                'label' => 'ticket.reduce_rate',
                'required' => false
            ]);


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
            'validation_groups' => [
                'stepTwo'
            ]
        ]);
    }



}

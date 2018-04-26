<?php

namespace AppBundle\Form;

use AppBundle\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitDate', DateType::class, [
                'widget'=> 'single_text',
                'label' => 'Jour de visite '
            ])
            ->add('typeOfTicket', ChoiceType::class, [
                'choices' => [
                    'Journée' => Booking::TYPE_OF_TICKET_DAY,
                    'Demi-journée' => Booking::TYPE_OF_TICKET_HALFDAY,
                ],
                'label'=> 'Type de ticket '
            ])
            ->add('numberOfTickets', ChoiceType::class,[
                'choices' => array_combine(
                    range(Booking::NB_TICKET_MIN,Booking::NB_TICKET_MAX),
                    range(Booking::NB_TICKET_MIN,Booking::NB_TICKET_MAX)),
                'label' => 'Nombre de tickets '])
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail '
            ])
            ->add('Valider', SubmitType::class)
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'validation_groups' => [
                'stepOne'
            ]
        ]);
    }

}

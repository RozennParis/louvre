<?php

namespace AppBundle\Form;

use AppBundle\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
                'label' => 'booking.visitDate',
                'format'=> 'dd/MM/yyyy',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'data-provide' => 'datepicker',
                    ],
                'required' => true
            ])
            ->add('typeOfTicket', ChoiceType::class, [
                'choices' => [
                    'booking.day' => Booking::TYPE_OF_TICKET_DAY,
                    'booking.half_day' => Booking::TYPE_OF_TICKET_HALFDAY,
                ],
                'label'=> 'booking.type_of_ticket'
            ])
            ->add('numberOfTickets', ChoiceType::class,[
                'choices' => array_combine(
                    range(Booking::NB_TICKET_MIN,Booking::NB_TICKET_MAX),
                    range(Booking::NB_TICKET_MIN,Booking::NB_TICKET_MAX)),
                'label' => 'booking.number_of_tickets'])
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'booking.email_error',
                'required' => true,
                'first_options' => ['label' => 'booking.email'],
                'second_options' => ['label' => 'booking.repeat_mail']
            ])
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

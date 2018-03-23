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
            ->add('visitDate', DateType::class, array(
                'widget'=> 'single_text'
            ))
            ->add('typeOfTicket', ChoiceType::class, array(
                'choices' => array(
                    'Journée' => Booking::TYPE_OF_TICKET_DAY,
                    'Demi-journée' => Booking::TYPE_OF_TICKET_HALFDAY,
                ),
            ))
            ->add('numberOfTickets', ChoiceType::class,[
                'choices' => array_combine(
                    range(Booking::NB_TICKET_MIN,Booking::NB_TICKET_MAX),
                    range(Booking::NB_TICKET_MIN,Booking::NB_TICKET_MAX))
                    ])
            ->add('email', EmailType::class)
            ->add('save', SubmitType::class);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Booking::class
        ));
    }

}

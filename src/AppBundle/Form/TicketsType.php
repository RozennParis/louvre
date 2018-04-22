<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 29/03/18
 * Time: 11:39
 */

namespace AppBundle\Form;


use AppBundle\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketsType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'validation_groups' => [
                'stepTwo'
            ]
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tickets', CollectionType::class, [
                'entry_type' => TicketType::class,
                'entry_options' => [
                    'label'=> false]
            ]);
    }

}

<?php

namespace AppBundle\Form;


use AppBundle\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class BookingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',  RepeatedType::class, array(
                'type' => EmailType::class,
                'invalid_message' => 'error.Les addresses ne correspondent pas.',
                'required' => true,
                'first_options' => array('label' => 'orderstep_1.action.chooseEmail'),
                'second_options' => array('label' => 'oderstep_1.action.confirmEmail'),
            ))
           ->add('visitDate', DateType::class, array(
               'label' => 'form.action.chooseVisitDate',
               'years' => range(date('Y'),date('Y')+2),
               'widget' => 'single_text',
           ))
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'orderstep_1.action.fullDay' => Booking::TYPE_FULL_DAY,
                   'orderstep_1.action.halfDay' => Booking::TYPE_HALF_DAY,
                ),
                'expanded' => true,
                'multiple' => false,

            ))

        ->add('numberOfTickets', IntegerType::class, array(
            'label' => 'orderstep_1.action.choiceNumberOfTicket'
        ))

        ->add('save', SubmitType::class, array(
            'label' => 'orderstep_1.action.order',
            'attr' => array(
                'class' => "btnEltform",
            )
    ));


    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Booking::class
        ));
    }


}

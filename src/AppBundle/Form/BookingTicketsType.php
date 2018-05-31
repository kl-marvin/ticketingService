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
                'invalid_message' => 'Les addresses ne correspondent pas.',
                'required' => true,
                'first_options' => array('label' => 'Entrez votre email'),
                'second_options' => array('label' => "Saisissez à nouveau votre email"),
            ))
           ->add('visitDate', DateType::class, array(
               'label' => 'Date de la visite',
               'format' => 'dd/MM/yyyy',
               'years' => range(date('Y'),date('Y')+2),
               'placeholder' => array(
                   'year' => 'Année',
                   'month' => 'Mois',
                   'day' => 'Jour',
                ),
           ))
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'Journée' => 'Journée',
                    'Demi-journée' => 'Demi-journée'
                ),
                // 'expanded' => true,
                // 'multiple' => false,

            ))

        ->add('numberOfTickets', IntegerType::class)

        ->add('save', SubmitType::class, array(
            'label' => 'Commander',
            'attr' => array(
                'class' => "waves-effect waves-light btn-large",
                'style' => "background-color: #c22131; margin-left: 35%",


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

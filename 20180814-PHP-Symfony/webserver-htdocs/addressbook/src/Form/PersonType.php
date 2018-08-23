<?php
// src/Form/PersonType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Person;
use App\Entity\Occupation;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname',
                  TextType::class,
                  array('label' => 'Nom',
                        'attr' => [
                            'data-bind' => 'event: { change: onFieldValueChange }, value: itemDetails().lastname'
                        ]
                  )
              )
            ->add('firstname',
                  TextType::class,
                  array('label' => 'Prénom',
                        'attr' => [
                            'data-bind' => 'event: { change: onFieldValueChange }, value: itemDetails().firstname'
                        ]
                  )
              )
            ->add('occupation',
                  EntityType::class,
                  array('label' => 'Profession',
                        'class' => Occupation::class,
                        'choice_label' => 'displayname',
                        'placeholder' => '-- choisissez une profession --',
                        'attr' => [
                            'data-bind' => 'event: { change: onFieldValueChange }, value: itemDetails().occupation.id'
                        ]
                  )
              )
            ->add('retired',
                  CheckboxType::class,
                  array('label' => 'Retraité',
                        'required' => false,
                        'attr' => [
                            'data-bind' => 'event: { change: onFieldValueChange }, checked: itemDetails().retired'
                        ]
                  )
              )
            ->add('telephone',
                  TelType::class,
                  array('label' => 'Téléphone',
                        'required' => false,
                        'attr' => [
                            'data-bind' => 'event: { change: onFieldValueChange }, value: itemDetails().telephone'
                        ]
                  )
              )
            ->add('email',
                  EmailType::class,
                  array('label' => 'Email',
                        'required' => false,
                        'attr' => [
                            'data-bind' => 'event: { change: onFieldValueChange }, value: itemDetails().email'
                        ]
                  )
              )
            ->add('comment',
                  TextareaType::class,
                  array('label' => 'Commentaires',
                        'required' => false,
                        'attr' => [
                            'data-bind' => 'event: { change: onFieldValueChange }, value: itemDetails().comment'
                        ]
                  )
              )
            ->add('createtimestamp',
                  DateTimeType::class,
                  array('label' => 'Date de création',
                        'required' => false,
                        'widget' => 'single_text',
                        'html5' => false,
                        'attr' => [
                            'readonly' => '1',
                            'data-bind' => "value: itemDetails().createtimestamp, visible: !isCreateAction()"
                        ]
                  )
              )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,                                     
        ]);
    }
}

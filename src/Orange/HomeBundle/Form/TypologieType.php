<?php

namespace Orange\HomeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypologieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('classification', 'entity', array(
                'class'         =>  'OrangeHomeBundle:Classification',
                'property'      =>  'libelle',
                'multiple'      =>  false,
                'expanded'      =>  false,
                'empty_value'   =>  "Sélectionner la thématique"
            ))
            ->add('enregistrer', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Orange\HomeBundle\Entity\Typologie'
        ));
    }
}

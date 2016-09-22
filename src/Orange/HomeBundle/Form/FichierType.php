<?php

namespace Orange\HomeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FichierType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('file', 'file')
            ->add('dateFin', 'date', array(
                'required'      =>  false
            ))
            ->add('type', 'entity', array(
                'class'         =>  'OrangeHomeBundle:Type',
                'property'      =>  'libelle',
                'multiple'      =>  false,
                'expanded'      =>  false,
                'empty_value'   =>  "Sélectionner le type de fichier"
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
            'data_class' => 'Orange\HomeBundle\Entity\Fichier'
        ));
    }
}

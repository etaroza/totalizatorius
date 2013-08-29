<?php

namespace Toto\TotalizerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('time')
            ->add('scoreHome')
            ->add('scoreAway')
            ->add('teamHome')
            ->add('teamAway');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Toto\TotalizerBundle\Entity\Game'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'toto_totalizerbundle_gametype';
    }
}

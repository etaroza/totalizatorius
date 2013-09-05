<?php

namespace Toto\TotalizerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BidType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('scoreHome')
            ->add('scoreAway')
            ->add('points')
            ->add('createdAt')
            ->add('user')
            ->add('competition')
            ->add('game');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Toto\TotalizerBundle\Entity\Bid'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'toto_totalizerbundle_bidtype';
    }
}

<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueChartQuestionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'attr' => array(
                    'class'=>'full-width'
                    )
                )
            )
            // ->add('chartType')            
            ->add('chartType', 'choice', array(
                'choices'  => array(
                    1  => 'Circle Donut',
                    2  => 'Semi circle donut',
                    3  => 'Column Basic',
                    4  => 'Pyramid',//TODO: Need to work - requires new highcharts.js and funnel.js - not compatible with older platform highcharts
                    5  => 'Category - Bar Chart',
                ),
            ))
            ->add('issueQuestion')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\IssueChartQuestion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_issuechartquestion';
    }
}

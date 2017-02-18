<?php

namespace MyShop\DefaultBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityTreeType extends AbstractType {

    public function buildView( FormView $view , FormInterface $form , array $options ) {
        $choices = [];

        foreach ( $view->vars['choices'] as $choice ) {
            $choices[] = $choice->data;
        }

        $choices = $this->buildTreeChoices( $choices );

        $view->vars['choices'] = $choices;

    }

    protected function buildTreeChoices( $choices , $level = 0 ) {

        $result = array();

        foreach ( $choices as $choice ){

            $result[] = new ChoiceView(
                $choice,
                $choice->getId(),
                str_repeat( '-' , $level ) . ' ' . $choice->getName(),
                []
            );

            if ( !$choice->getChildrenCategories()->isEmpty() )
                $result = array_merge(
                    $result,
                    $this->buildTreeChoices( $choice->getChildrenCategories() , $level + 1 )
                );

        }

        return $result;

    }

    public function setDefaultOptions( OptionsResolver $resolver ) {
        $resolver->setDefaults(array(
            'query_builder' => function ( EntityRepository $repo ) {
                return $repo->createQueryBuilder('e')->where('e.parentCategory IS NULL');
            }
        ));
    }

    public function getParent() {
        return EntityType::class;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'entity_tree';
    }
}
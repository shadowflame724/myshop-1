<?php

namespace MyShop\DefaultBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use MyShop\DefaultBundle\Form\Type\EntityTreeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model', TextType::class, [
                'label' => 'Модель товара'
            ])
            ->add('price', NumberType::class, [
                "label" => 'Цена товара'
            ])
            ->add('description', CKEditorType::class, [
                'label' => "Описание товара"
            ])
            ->add('iconPhoto', FileType::class, [
                'label' => 'Иконка товара',
                'mapped' => false,
                'required' => false
            ])
            ->add('category', EntityTreeType::class, [
                "class" => "MyShopDefaultBundle:Category",
                "choice_label" => "name",
                "label" => "Категория"
            ])
            ->add('isShowOnMainPage', CheckboxType::class, [
                'label' => 'Отображать товар на главной',
                'required' => false
            ])
            ->add('iconFile', FileType::class, [
                'mapped' => false,
                'label' => 'Иконка к товару',
                'required' => false
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyShop\DefaultBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myshop_defaultbundle_product';
    }


}

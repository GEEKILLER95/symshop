<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => false,
                'label' => 'Nom du produit'
            ])
            ->add('categorie', EntityType::class, [
                'required' => false,
                'placeholder' => '---Veuillez choisir une catégorie---',
                'class' => Categorie::class,
                'choice_label' => function (Categorie $categorie) 
                {
                    return $categorie->getNom();
                }
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "Description du produit"
                ],
                'label' => 'Description du produit'
                
            ])
            ->add('prix', MoneyType::class, [
                'required' => false,
                'label' => "Prix du produit"
            ])
            ->add('image', UrlType::class, [
                'required' => false,
                'label' => "Entrez l'URL de l'image du produit",
                'attr' => [
                    'placeholder' => "www.holdit.com/350x200"
                ]
                
            ])
            ->add('stock', NumberType::class, [
                'required' => false,
                'label' => "Stock disponible du produit"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}

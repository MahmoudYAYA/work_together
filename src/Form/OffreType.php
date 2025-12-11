<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('description', TextType::class, ['required' => false])
            ->add('nombreUnites', IntegerType::class)
            ->add('prixMensuel', MoneyType::class, [
                'currency' => 'EUR',
            ])
            ->add('prixAnnuelle', MoneyType::class, [
                'currency' => 'EUR',
            ])
            ->add('redctionAnnuelle', IntegerType::class, [
                'required' => false,
                'label' => 'Réduction annuelle (%)'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}

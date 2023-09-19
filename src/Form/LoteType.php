<?php

namespace App\Form;

use App\Entity\Lote;
use App\Entity\Produto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produto', EntityType::class,[
                'label'=>'Selecione o produto: ',
                'class'=>Produto::class,
            ])
            ->add('quantidade', IntegerType::class,[
                'label'=>'Informe a quantidade:',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lote::class,
        ]);
    }
}

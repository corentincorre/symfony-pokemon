<?php

namespace App\Form;

use App\Entity\Trade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class AddTradeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sender_pokemon', null, ['label' => 'Pokemon que vous voulez échanger'])
            ->add('receiver', null, ['label' => 'Partenaire d\'échange'])
            ->add('reciever_pokemon', null, ['label' => 'Pokemon que vous recevrez'])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trade::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Userlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserlistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adsoyad')
            ->add('kullaniciadi')
            ->add('sifre')
            ->add('mail')
            ->add('tarih')
            ->add('uyetipi')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Userlist::class,
            'csrf_protection'=>false,
        ]);
    }
}

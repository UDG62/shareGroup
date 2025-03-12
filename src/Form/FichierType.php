<?php

namespace App\Form;

use App\Entity\Fichier;
use App\Entity\User;
use App\Entity\Scategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class FichierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('fichier', FileType::class, array('label' => 'Fichier', 'mapped'=>false,'attr' => ['class'=>
'form-control'], 'label_attr' => ['class'=> 'fw-bold'],'constraints' => [
new File([
'maxSize' => '200k',
'mimeTypes' => [
'application/pdf',
'application/x-pdf',
'image/jpeg',
'image/png',
],
'mimeTypesMessage' => 'Le site accepte uniquement les fichiers PDF, PNG et JPG',
])
],))
->add('user', EntityType::class, [
'class' => User::class,
'attr' => ['class'=> 'form-control'], 'label_attr' => ['class'=> 'fw-bold'],
'choice_label' => function($user) {
return $user->getNom() . ' ' . $user->getPrenom();
},
'query_builder' => function (EntityRepository $er) {
return $er->createQueryBuilder('u')
->orderBy('u.nom', 'ASC')
->addOrderBy('u.prenom', 'ASC');
},
]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Fichier::class,
        ]);
    }
}

<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class Register_form extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('full_name', TextType::class, [ // los nombres tinene que ser lo del campo de la base de datos
                        'label' => 'Nombre'
                    ])
                    ->add('email', EmailType::class, [
                        'label' => 'Email: '
                    ])
                    ->add('password', PasswordType::class, [
                        'label' => 'Contrasena: '
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Registrarte: '
                    ]);
    }
}
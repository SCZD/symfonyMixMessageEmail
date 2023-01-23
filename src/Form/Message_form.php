<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class Message_form extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('toemail', EmailType::class, [ // los nombres tinene que ser lo del campo de la base de datos
                        'label' => 'A quien: '
                    ])
                    ->add('subject', TextType::class, [
                        'label' => 'Ausnto: '
                    ])
                    ->add('compose_email', TextareaType::class, [
                        'label' => 'Texto: '
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Enviar: '
                    ]);
    }
}
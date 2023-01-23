<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class Message_form_reply extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){


        $builder->add('subject', TextType::class, [
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
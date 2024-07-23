<?php

namespace App\League\Infrastructure\Form\Symfony\League;

use App\League\Application\DTO\CreateLeagueData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

final class CreateLeagueForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('maxNumberOfParticipants', IntegerType::class, [
                'constraints' => [
                    new Range(['min' => 2])
                ]
            ])
            ->add('isPrivate', CheckboxType::class, [
                'required' => false,
            ])
            ->add('password', TextType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Create league']);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            /** @var CreateLeagueData $data */
            $data = $event->getData();

            if ($data->isPrivate && !$data->password) {
                $form->get('password')
                    ->addError(new FormError('A private league must have a password'));

            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateLeagueData::class,
        ]);
    }
}
<?php

namespace App\User\Infrastructure\Form\Symfony;

use App\Shared\Domain\Utils\DateTime\DateTimeProviderInterface;
use App\User\Application\DTO\SuspendUserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

final class SuspendUserForm extends AbstractType
{
    public function __construct(private readonly DateTimeProviderInterface $dateTimeProvider)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('suspendedTill', DateTimeType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull()
                ]
            ])
            ->add('reason', TextType::class)
            ->add('submit', SubmitType::class, ['label' => 'Suspend user']);

        // !! This validation is in place just for UX experience. I would not consider it a real duplication of the domain logic !!
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if ($data->suspendedTill < $this->dateTimeProvider->now()) {
                $form->get('suspendedTill')->addError(new FormError('The suspension date must be in the future'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SuspendUserData::class,
        ]);
    }
}
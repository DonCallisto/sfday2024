<?php

namespace App\User\Infrastructure\Controller\Symfony\ValueResolver;

use App\User\Domain\Model\User\User;
use App\User\Domain\Model\User\UserID;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\Uuid;

final class UserResolver implements ValueResolverInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== User::class) {
            return [];
        }

        if ($request->attributes->has('id')) {
            $value = $request->attributes->get('id');
        }

        $value = $value ?? $request->attributes->get($argument->getName());
        if (!is_string($value)) {
            return [];
        }

        return [$this->userRepository->findById(new UserID(Uuid::fromString($value)))];
    }
}
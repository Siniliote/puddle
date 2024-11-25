<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ResetPasswordRequest;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Persistence\Repository\ResetPasswordRequestRepositoryTrait;
use SymfonyCasts\Bundle\ResetPassword\Persistence\ResetPasswordRequestRepositoryInterface;

/**
 * @extends ServiceEntityRepository<ResetPasswordRequest>
 */
class ResetPasswordRequestRepository extends ServiceEntityRepository implements ResetPasswordRequestRepositoryInterface
{
    use ResetPasswordRequestRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPasswordRequest::class);
    }

    /**
     * @param User $user
     */
    public function createResetPasswordRequest(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken): ResetPasswordRequestInterface
    {
        return new ResetPasswordRequest($user, $expiresAt, $selector, $hashedToken);
    }

    /**
     * Override trait method because of using ulid for user ID.
     *
     * @param User $user
     */
    public function getMostRecentNonExpiredRequestDate(object $user): ?\DateTimeInterface
    {
        // Normally there is only 1 max request per use, but written to be flexible
        /** @var ResetPasswordRequestInterface $resetPasswordRequest */
        $resetPasswordRequest = $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameter('user', $user->getId(), 'ulid')
            ->orderBy('t.requestedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneorNullResult()
        ;
        if (null !== $resetPasswordRequest && !$resetPasswordRequest->isExpired()) {
            return $resetPasswordRequest->getRequestedAt();
        }

        return null;
    }

    /**
     * Override trait method because of using ulid for user ID.
     */
    public function removeResetPasswordRequest(ResetPasswordRequestInterface $resetPasswordRequest): void
    {
        /** @var User $user */
        $user = $resetPasswordRequest->getUser();

        $this->createQueryBuilder('t')
            ->delete()
            ->where('t.user = :user')
            ->setParameter('user', $user->getId(), 'ulid')
            ->getQuery()
            ->execute()
        ;
    }
}

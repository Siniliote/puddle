<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Config\Role;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/user')]
#[IsGranted(Role::ADMIN->value)]
class UserController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    /**
     * @return array<string, User[]>
     */
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    #[Template('user/index.html.twig')]
    public function index(UserRepository $userRepository): array
    {
        return [
            'users' => $userRepository->findAll(),
        ];
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/confirmation/email/{id}', name: 'app_confirmation_email', methods: ['POST'])]
    public function sendEmailConfirmation(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('confirmation_email'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@puddle.dev', 'Puddle Mail Bot'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
        }

        $this->addFlash('success', 'Email confirmation sent');

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class UserFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(name: 'app_login');
    }

    public function authenticate(Request $request): Passport
    {
        $identifier = $request->request->get('_username');
        $password = $request->request->get('_password');

        return new Passport(
            credentials: new PasswordCredentials($password),
            badges: [],
            userBadge: new UserBadge($identifier),
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $session = $request->getSession();

        $redirect = $this->getTargetPath(firewallName: $firewallName, session: $session) ?? $this->urlGenerator->generate('app_home');

        return new RedirectResponse($redirect);
    }
}

<?php declare(strict_types=1);


namespace App\Security;


use App\Entity\User;
use App\Redis\RedisServiceInterface;
use MessageInfo\UserAPIDataProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    private CsrfTokenManagerInterface $csrfTokenManager;

    /**
     * @var \App\Redis\RedisServiceInterface
     */
    private RedisServiceInterface $redisService;

    /**
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     * @param \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrfTokenManager
     * @param \App\Redis\RedisServiceInterface $redisService
     */
    public function __construct(\Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator, \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrfTokenManager, \App\Redis\RedisServiceInterface $redisService)
    {
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->redisService = $redisService;
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('login');
    }

    public function supports(Request $request)
    {
        return 'login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = json_decode($this->redisService->get('user:' . $credentials['username']), true);
        if ($user && isset($user['email'])) {
            $userDTO = new UserAPIDataProvider();
            $userDTO->fromArray($user);

            $userEnity =  new User();
            $userEnity->setId($userDTO->getUserId());
            $userEnity->setRole($userDTO->getRole());
            $userEnity->setUsername($userDTO->getEmail());
            $userEnity->setStateIso($userDTO->getStateIso());

            return $userEnity;
        }

        throw new CustomUserMessageAuthenticationException(
           'user not found'
        );
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $credentials['password'] === 'test123';
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        $path = 'home';
        return new RedirectResponse($this->urlGenerator->generate($path));
    }


}

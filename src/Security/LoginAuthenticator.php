<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;


class LoginAuthenticator extends AbstractGuardAuthenticator
{
    private $passwordEncoder;
    private $user;
    private $mdpCheck;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,RouterInterface $router)
    {
        $this->passwordEncoder = $passwordEncoder;
         $this->router = $router;
    }

   public function supports(Request $request)
    {
        return $request->get("_route") === "login" && $request->isMethod("POST");
    }

    public function getCredentials(Request $request)
    {
        return [
            'email' => $request->request->get("email"),
            'password' => $request->request->get("password")
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['email']);
        /* $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email ou mot de passe incorrect');
        }

        return $user;*/
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $this->user=$user;
        $login=false;
        $this->mdpCheck=$this->passwordEncoder->isPasswordValid($user, $credentials['password']);
        //verif credentials et etat compte pour savoir si on peut se connecter
        if($this->user!=null)
        {
            if($this->mdpCheck&&$this->user->getEtatCompte()===1)
            {
                $login=true;
            }
        }
        return $login;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        //definition message erreur suivant origin failure (credentials ou etat compte)
        $message="credentials";
        if($this->user!=null)
        {
            if($this->user->getEtatCompte()===0&&$this->mdpCheck)
            {
                $message="activation";
            }
        }
        return new JsonResponse([
           'login' => false,'message'=>$message
       ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        //générer token
        return new RedirectResponse($this->router->generate('token',['id'=>$this->user->getId()]));
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([
           'error' => 'Access Denied'
       ]);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}

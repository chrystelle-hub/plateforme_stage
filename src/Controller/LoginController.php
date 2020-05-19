<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    //redirige vers loginAuthenticator
    public function login()
    {
         /*function genererChaineAleatoire($longueur, $listeCar = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
            {
             $chaine = '';
             $max = mb_strlen($listeCar, '8bit') - 1;
             for ($i = 0; $i < $longueur; ++$i) {
             $chaine .= $listeCar[random_int(0, $max)];
             }
             return $chaine;
            }
        $apiToken=genererChaineAleatoire(50);
        $user = $security->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $user->setApiToken($apiToken);
        $entityManager->flush();
        return $this->json(['api_token' => $apiToken]);*/
    }
    /**
     * @Route("/token", name="token")
     */
    //generation token/update user au login
    public function token(Request $request)
    {
         function genererChaineAleatoire($longueur, $listeCar = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
            {
             $chaine = '';
             $max = mb_strlen($listeCar, '8bit') - 1;
             for ($i = 0; $i < $longueur; ++$i) {
             $chaine .= $listeCar[random_int(0, $max)];
             }
             return $chaine;
            }
        $apiToken=genererChaineAleatoire(50);
        $id=$request->query->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $user= $this->getDoctrine()->getRepository(User::class)->find($id);
        $user->setApiToken($apiToken);
        $entityManager->flush();
        return new JsonResponse([
           'login' => $apiToken
       ]);
    }

     /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        
        return new jsonResponse([
            'login'=>'ok'
        ]);
    }
     /**
     * @Route("/deconnexion", name="logout2")
     */
     //set token user à 'logout' quand il se déconnecte
    public function deconnexion(Request $request)
    {
        $user= $this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $request->request->get('X-AUTH-TOKEN')]);
        $entityManager = $this->getDoctrine()->getManager();
        $user->setApiToken('logout');
        $entityManager->flush();
        $this->logout();
        return new jsonResponse([
            'login'=>'ok'
        ]);
    }
    /**
     * @Route("/testconnexion", name="test")
     */
    //pour savoir rapidement si on à l'accès à la page et donc si a un token valide
    public function connexion()
    {
        return new jsonResponse([
            'login'=>'ok'
        ]);
    }
    /**
     * @Route("/testRole", name="test_role")
     */
    public function connexionRole(EntityManagerInterface $em, Request $request)
    {
        $user= $em->getRepository(User::class)->findOneBy(['apiToken' => $request->request->get('X-AUTH-TOKEN')]);
        $admin=false;
        $response = new Response();
        if($user)
        {
           
            $connexionRole = $em->getRepository(User::class)->userHasRole($user->getId());
            if($connexionRole != null)
            {
                $admin = true;
            }
        }
        $response->setContent(Json_encode(
            [
                'connexion' => $admin
            ]
        ));
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}

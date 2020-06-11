<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Form\ReinitialisationType;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $user->setApiToken('logout');
        $entityManager = $this->getDoctrine()->getManager();
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
    /**
     * @Route("/reinitialisation", name="reinitialisation_mot_de_passe")
     */
    public function reinitialisation(EntityManagerInterface $em, Request $request, \Swift_Mailer $mailer)
    {
        function genererChaineAleatoire2($longueur, $listeCar = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
            {
             $chaine = '';
             $max = mb_strlen($listeCar, '8bit') - 1;
             for ($i = 0; $i < $longueur; ++$i) {
             $chaine .= $listeCar[random_int(0, $max)];
             }
             return $chaine;
            }
       
        $form = $this->createForm(ReinitialisationType::class);
        $values=$request->request->all();
        $form->submit($values); 
        $errors = array();
        $response= new Response;

        if ($form->isValid())
        {
            $data=$form->getData();
            $email = $data['email'];
            $user=$em->getRepository(User::class)->findOneByEmail($email);
            $token=genererChaineAleatoire2(50);
            $user->setToken($token);
            $em->flush();

            $url = 'http://127.0.0.1:8080/plateforme_stage/appli/vue/reinitialisation-mot-de-passe.html?token='. $token;

            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('votre@adresse.fr')
                ->setTo($user->getEmail())
                ->setBody("Bonjour,<br><br> Une demande de réinitialisation de mot de passe a été effectué. Veuillez cliquer sur le lien suivant :" . $url)
            ;

            $mailer->send($message);
            
            if($user){
                
            $response->setContent(json_encode(
                [
                    'email'=>'ok',
                    'message'=>'Un email vous a été envoyé pour pouvoir changer votre mot de passe',
                    'mail'=>$message->getBody()
            
                ]
            ));

            } else {

                $response->setContent(json_encode(
                    [
                        'email'=>'pas ok',
                            
                    ]

                ));
            }

        } else
        {
           foreach ($form->getErrors(true) as $error) 
            {
                $errors[$error->getOrigin()->getName()][] = $error->getMessage();
            }
            $response->setContent(json_encode(
                [
                    'erreur'=>$errors,
                ]
            ));
        } 
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;

    }
      
    /**
     * @Route("/resetPassword", name="resert_password")
     */
    public function ResetPassword(EntityManagerInterface $em, Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $em->getRepository(User::class)->findOneBy(['token' => $request->request->get('token')]);
        $form = $this->createForm(ResetPasswordType::class);
        $values=$request->request->all();
        $form->submit($values); 
        $errors = array();
        $response= new Response;

        if ($form->isValid())
        {
            if($form->get('password')->getData()!=null)
            {
                $user->setPassword($passwordEncoder->encodePassword(
                    $user,$form->get('password')->getData()));
            }
           $user->setToken(null);
            $em->persist($user);
            $em->flush();
            
            $response->setContent(json_encode([
                'modif'=>'ok',
                'user'=>$user->getEmail()
            ]));
        }
        else
        {
            foreach ($form->getErrors(true) as $error) 
            {
                $errors[$error->getOrigin()->getName()][] = $error->getMessage();
            }
           $response->setContent(json_encode(
                [
                    'modif'=>'pas ok',
                    'erreur'=>$errors
                ]
            )); 
        }
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;

    }
}

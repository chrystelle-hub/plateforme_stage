<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Form\RegisterFormType;
use App\Entity\Formation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $user = new User();
        $response = new Response();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->submit($request->request->all()); 
        //creation nouvel user
        if ($form->isValid()) 
        {
            $user->setPassword($passwordEncoder->encodePassword(
                    $user,$form->get('password')->getData()));
            $user->setDateCreationPassword(new \DateTime());
            $user->setListePwd([$user->getPassword(),null,null,null,null]);
            $user->setEtatCompte(0);
            $user->setStage(0);
            $formation=$this->getDoctrine()->getRepository(Formation::class)->find($request->get('formation'));
            $user->addFormation($formation);
            $entityManager = $this->getDoctrine()->getManager();
           
            $entityManager->persist($user);
            $entityManager->flush();
            $response->setContent(json_encode(
                [
                    'inscription'=>'ok'
                ]
            ));
            
           }
      
        else
        {
             $errors = array();
            foreach ($form->getErrors(true) as $error) 
            {
                $errors[$error->getOrigin()->getName()][] = $error->getMessage();
            }

           $response->setContent(json_encode(
                [
                    'inscription'=>'pas ok',
                    'erreur'=>$errors
                ]
            )); 
        }
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}

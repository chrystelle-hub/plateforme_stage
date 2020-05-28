<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Candidature;
use App\Entity\Formation;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Form\RegisterFormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ProfilController extends AbstractController
{
     /**
     * @Route("/profil/data", name="profilData")
     */
     public function data(Request $request)
     {
         $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $request->request->get('X-AUTH-TOKEN')]);
        $response=new Response();
        $response->setContent(json_encode([
            'nom'=>$user->getNom(),
            'prenom'=>$user->getPrenom(),
            'mail'=>$user->getEmail(),
            'stage'=>$user->getStage(),
        ]));
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
       
     }
    /**
     * @Route("/profil/modif", name="profilModif")
     */
    public function Modif(UserPasswordEncoderInterface $passwordEncoder,Request $request)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $request->request->get('X-AUTH-TOKEN')]);
        $form = $this->createForm(RegisterFormType::class, $user);
        //remove champs nom form
        $form->remove('email');
        $form->remove('password');
        $form->remove('formation');
        $form->add('password', PasswordType::class,  array('mapped' => false,'required'=>false,'constraints'=>[
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]
            ));
        $form->add('stage',NumberType::class,[
            'invalid_message' =>'erreur',
            'required'=>false
        ]);
        
        $values=$request->request->all();
        unset($values["X-AUTH-TOKEN"]);
         
        $errors = array();
        $response=new Response();
        $form->submit($values); 
        if ($form->isValid())
        {
            if($form->get('password')->getData()!=null)
            {
                $user->setPassword($passwordEncoder->encodePassword(
                    $user,$form->get('password')->getData()));
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $response->setContent(json_encode([
                'modif'=>'ok'
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


    /**
     * @Route("/profil/candidature", name="profilCandidature")
     
     */
    //recup candidatures user
    public function candidatures(Request $request)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $request->request->get('X-AUTH-TOKEN')]);
        $response=new Response();
        $candidatures=$user->getCandidatures();
        $candidature_user=[];
        foreach($candidatures as $candidature)
        {
            $candidature_user[]=['id'=>$candidature->getId(),'date'=>$candidature->getDateEnvoieCandidature(),'entreprise'=>$candidature->getEntreprise()->getNom(), 'etat'=>$candidature->getEtat()];
        }
         
        $response->setContent(json_encode(
            [
                'candidature'=>$candidature_user
            ]
        )); 
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
     /**
     * @Route("/profil", name="profil_id")
     */
    
    public function index(Request $request)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $request->request->get('X-AUTH-TOKEN')]);
     
        $formations=$user->getFormation();
        $formations_liste=[];
        foreach($formations as $formation)
        {
            $formations_liste[]=$formation->getNom();
        }
        $response=new Response();
        $candidatures=$user->getCandidatures();
        $candidature_user=[];
        foreach($candidatures as $candidature)
        {
            $candidature_user[]=[
                'id'=>$candidature->getId(),
                'date'=>$candidature->getDateEnvoieCandidature(),
                'entreprise'=>$candidature->getEntreprise()->getNom(), 
                'etat'=>$candidature->getEtat()];
        }
        $infos_user = [];
        
    
        $infos_user[]=[
            'id'=>$user->getId(),
            'nom'=>$user->getNom(),
            'prenom'=>$user->getPrenom(),
            'email'=>$user->getEmail(),
            'role'=>$user->getRoles(),
            'stage'=>$user->getStage(),
            'formation'=>$formations_liste
        ];
        
        $response->setContent(json_encode(
            [
                'user'=>$infos_user,
                'candidature'=>$candidature_user
            ]
        )); 
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
       //return $this->json(['result' => $request->get('X-AUTH-TOKEN')]);
    
    }
}

<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\User;
use App\Form\AjoutFormationType;
use App\Repository\FormationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/list/user", name="admin_list_user")
     */
    public function list(UserRepository $userRepository, SerializerInterface $serialize)
    {
        $users = $userRepository->findAll();

        $data = $serialize->serialize($users,'json',['groups'=>'formation:users']);
        
        $response = new Response($data,200, [
            'Content-Type' =>'application/json'
        ]);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        
        return $response;
    }

     /**
     * @Route("/list/formation", name="admin_list_formation", methods={"GET"})
     */
    public function listFormation(FormationRepository $formationRepository, SerializerInterface $serialize)
    {
        $formation = $formationRepository->findAll();

        $data = $serialize->serialize($formation,'json', ['groups'=>'formation']);
        
        $response = new Response($data,200, [
            'Content-Type' =>'application/json'
        ]);

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
     /**
     * @Route("/formation/users", name="admin_list_formation_user")
     */
    public function FormationUsers(FormationRepository $formationRepository, SerializerInterface $serialize,Request $request, EntityManagerInterface $em)
    {
        $user= $em->getRepository(User::class)->findOneBy(['apiToken' => $request->request->get('X-AUTH-TOKEN')]);
        $formations = $user->getFormation();
        $response = new Response();

        $liste_formations = [];

        foreach($formations as $formation )
        {
            $liste_formations[] = ['id'=> $formation->getId()];
        }
        $formation_users = $em->getRepository(User::class)->findUsers($formation->getId());

        $liste_users = [];

        foreach($formation_users as $formation_user)
        {
            $nb_candidature = count($formation_user->getCandidatures());
            /*$candidatures_user = $formation_user->getCandidatures();
            $candidature_user=[];
            foreach($candidatures_user as $candidature)
            {
                $candidature_user[]=[
                    'id'=>$candidature->getId(),
                    'date'=>$candidature->getDateEnvoieCandidature(),
                    'entreprise'=>$candidature->getEntreprise()->getNom(), 
                    'etat'=>$candidature->getEtat(),
                ];
            }*/
            $liste_users[] = [
                'id' => $formation_user->getId(),
                'nom' => $formation_user->getNom(),
                'prenom'=> $formation_user->getPrenom(),
                'date_creation_password'=> $formation_user->getDateCreationPassword(),
                'etat_compte'=>$formation_user->getEtatCompte(),
                'roles'=>$formation_user->getRoles(),
                'stage'=>$formation_user->getStage(),
                'nombre_candidature'=>$nb_candidature
            ];
        }

        $response->setContent(json_encode(
            [
                'formation'=>$liste_formations,
                'users'=>$liste_users
            ]
        ));

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     *
     * @Route("/validate", name="admin_validate_user")
     */
    public function validation(Request $request, EntityManagerInterface $em)
    {
        $userValidate = $em->getRepository(User::class)->find($request->request->get('id'));

        $data = json_decode($request->getContent());
        
        if($userValidate->getEtatCompte() != 1)
        {
            $userValidate->setEtatCompte(1);
            $em->persist($userValidate);
            $em->flush();

        $data = [
            'status'=>200,
            'message'=>'le compte est validé'
        ];

        $response = new JsonResponse($data);

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;

        } 
        else {

            $data = [
                'statut' => 500,
                'message' => 'le compte est déjà validé'
            ];
        


        return $response;
        }


    }

     /**
     *
     * @Route("/desactive", name="admin_deasctive_user")
     */
    public function desactivation(Request $request, EntityManagerInterface $em)
    {
        $userDesactive = $em->getRepository(User::class)->find($request->request->get('id'));

        $data = json_decode($request->getContent());
        
        if($userDesactive->getEtatCompte() === 1)
        {
            $userDesactive->setEtatCompte(0);
            $em->persist($userDesactive);
            $em->flush();

        $data = [
            'status'=>200,
            'message'=>'le compte est désactivé'
        ];

        $response = new JsonResponse($data);

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;

        } 
        else {

            $data = [
                'statut' => 500,
                'message' => 'le compte est déjà désactivé'
            ];
        


        return $response;
        }


    }

    /**
     * @Route("/add/formation", name="admin_add_formation", methods ={"POST"})
     */
    public function addFormation(Request $request, SerializerInterface $serializer ,EntityManagerInterface $em)
    {
        $formation = new Formation();
        $form = $this->createForm(AjoutFormationType::class,$formation);
        $values = $request->request->all();
        unset($values["X-AUTH-TOKEN"]);
        $form->submit($values);

        if($form->isValid())
        {
           
            $em-> persist($formation);
            $em->flush();

            $data = [
                'status' => 201,
                'message' => 'la formation a bien été ajoutée'
            ];
            $response = new JsonResponse($data, 201);

           
        }
         else
         {
            $response= new Response();
            $error=[];
            foreach ($form->getErrors(true) as $error) 
            {
                $errors[$error->getOrigin()->getName()][] = $error->getMessage();
            }
           $response->setContent(json_encode(
                [
                    'ajout'=>'pas ok',
                    'erreur'=>$errors
                ]
            )); 
         }
        $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
    }

     /**
     * @Route("/add/role", name="admin_add_role")
     */
    public function addRoleAdmin(Request $request,SerializerInterface $serializer, EntityManagerInterface $em)
    {
        
        $error=[];
        $role = $em->getRepository(User::class)->find($request->request->get('id'));

        $data = json_decode($request->getContent());

        $role->setRoles(["ROLE_ADMIN"]);

        if (!$error)
        {
            $em->flush();

            $data = [
                'status'=>201,
                'message'=>'role ajouté'
            ];

            $response = new JsonResponse($data, 201);

            $response->headers->set('Access-Control-Allow-Origin', '*');
            
            return $response;

        } else {

            $error = $serializer->serialize($error, 'json');

            return new Response($error, 500, [
                'Content-Type' => 'application/json'
            ]);
 
        }
        
    }
    
}
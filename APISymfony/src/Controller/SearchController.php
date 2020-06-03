<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Entreprise;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\SearchType;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {
        $form = $this->createForm(SearchType::class,null);
        $values=$request->request->all();
        //remove data qui ne servent pas dans le formulaire
        unset($values["X-AUTH-TOKEN"]);
        $form->submit($values); 
        $errors = array();
        $response= new Response;
        if ($form->isValid())
        {
            //recup données form pour faire la recherche
            $data=$form->getData();
            $departement=$data['departement'];
            $nom=$data['nom'];
            $secteur_activite=$data['secteur_activite'];
            $formation=$data['formation'];
           
            $entreprise_liste=[];
            //requete de recherche
            $entreprises = $this->getDoctrine()->getRepository(Entreprise::class)->findBySearch($departement,$nom,$secteur_activite,$formation);
            //recup infos
            foreach ($entreprises as  $entreprise) 
            {
                $formation_liste=[];
                $id=$entreprise->getId();
                $nom=$entreprise->getNom();
                $secteur_activite=$entreprise->getSecteurActivite();
                $code_postal=$entreprise->getCodePostal();
                $ville = $entreprise->getVille();
                $formations=$entreprise->getFormation();

                foreach($formations as $formation)
                {
                    $formation_liste[]=$formation->getTag();
                }
                $entreprise_liste[]=[
                    'id'=>$id,
                    'nom'=>$nom,
                    'code_postal'=>$code_postal,
                    'ville'=>$ville,
                    'secteur_activite'=>$secteur_activite,
                    'tag'=>$formation_liste];
            }
            $response->setContent(json_encode(
                [
                    'entreprise'=>$entreprise_liste
                    
                ]
            )); 
        
        }
        else
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
     * @Route("/admin/search", name="admin_search")
     */
    public function searchEmail(Request $request)
    {
        $form = $this->createForm(SearchType::class,null);
        $values=$request->request->all();
        $form->submit($values); 
        $errors = array();
        $response= new Response;
        if ($form->isValid())
        {
            $data=$form->getData();
            $email = $data['email'];
            $user_liste = [];

            $users = $this->getDoctrine()->getRepository(User::class)->findByEmail($email);

            foreach($users as $user)
            {
                $id=$user->getId();
                $nom=$user->getNom();
                $prenom=$user->getPrenom();
                $roles=$user->getRoles();

                $user_liste = [
                    'id'=>$id,
                    'nom'=>$nom,
                    'prenom'=>$prenom,
                    'roles'=>$roles
                ];
            }
            $response->setContent(json_encode(
                [
                    'user'=>$user_liste,
                    
                ]
            ));
        }
        else
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
}

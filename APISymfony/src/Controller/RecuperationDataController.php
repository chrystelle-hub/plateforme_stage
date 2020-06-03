<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Candidature;
use App\Entity\Contact;
use App\Entity\Entreprise;
use App\Entity\Formation;

class RecuperationDataController extends AbstractController
{
    /**
     * @Route("/recuperation/data/contact", name="recuperation_data_contact")
     */
    public function contact(Request $request)
    {
        $contact=$this->getDoctrine()->getRepository(Contact::class)->find($request->get('id'));
        $response=new Response();
        $contact_infos=['id'=>$contact->getId(),'nom'=>$contact->getNom(),'fonction'=>$contact->getFonction(),'tel'=>$contact->getTel(),'mail'=>$contact->getMail(),'linkedin'=>$contact->getLinkedin(),'entreprise' => $contact->getEntreprise()->getNom(),'entrepriseId' => $contact->getEntreprise()->getId()];    

        $response->setContent(json_encode(
            [
                'contact'=>$contact_infos
            ]
            )); 
        
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
     /**
     * @Route("/recuperation/data/candidature", name="recuperation_data_candidature")
     */
    public function candidature(Request $request)
    {
        $candidature=$this->getDoctrine()->getRepository(Candidature::class)->find($request->get('id'));
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $request->request->get('X-AUTH-TOKEN')]);
        $response=new Response();
        if($user->getId()===$candidature->getUser()->getId())
        {
             $candidature_infos=['id'=>$candidature->getId(),'moyen'=>$candidature->getMoyen(),'etat'=>$candidature->getEtat(),'reponse'=>$candidature->getReponse(),'delai_reponse'=>$candidature->getDelaiReponse(),'date'=>$candidature->getDateEnvoieCandidature(),'entreprise' => $candidature->getEntreprise()->getNom()];    

            $response->setContent(json_encode(
            [
                'candidature'=>$candidature_infos
            ]
            )); 
        }
        else
        {
            $response->setContent(json_encode(
            [
                'candidature'=>'access denied'
            ]
            )); 
        }
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
     /**
     * @Route("/recuperation/data/entreprise", name="recuperation_data_entreprise")
     */
    public function entreprise(Request $request)
    {
        $entreprise=$this->getDoctrine()->getRepository(Entreprise::class)->find($request->get('id'));
        $response=new Response();
        $formations=$entreprise->getFormation();
        $formations_liste=[];
        foreach($formations as $formation)
        {
            $formations_liste[]=$formation->getId();
        }
        $entreprise_infos=[
            'id'=>$entreprise->getId(),
            'nom'=>$entreprise->getNom(),
            'secteur_activite'=>$entreprise->getSecteurActivite(),
            'adresse'=>$entreprise->getAdresse(),
            'tel'=>$entreprise->getTel(),
            'mail'=>$entreprise->getMail(),
            'code_postal'=>$entreprise->getCodePostal(),
            'ville'=>$entreprise->getVille(),
            'departement' => $entreprise->getDepartement(),
            'historique' => $entreprise->getHistoriqueModif(),
            'formation'=>$formations_liste
        ];    

        $response->setContent(json_encode(
            [
                'entreprise'=>$entreprise_infos
            ]
            )); 
        
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
     /**
     * @Route("/recuperation/data/formationactuelles", name="recuperation_data_formationactuelles")
     */
     //recup formation en cours
    public function formationactuelles(Request $request)
    {
        $date=new \DateTime();
        $formations=$this->getDoctrine()->getRepository(Formation::class)->findByAnnee(  ['promotion' => $date->format('Y')]);
        $response=new Response();
       
        $formations_liste=[];
        foreach($formations as $formation)
        {
            $formations_liste[]=['tag'=>$formation->getTag(),'id'=>$formation->getId()];
        }

        $response->setContent(json_encode(
            [
                'formation'=>$formations_liste
            ]
            )); 
        
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
     /**
     * @Route("/recuperation/data/formationuser", name="recuperation_data_formationuser")
     */
     //recup formation en cours
    public function formationUser(Request $request)
    {
        
        $user=$this->getDoctrine()->getRepository(User::class)->findOneBy(['apiToken' => $request->request->get('X-AUTH-TOKEN')]);
        $formations=$user->getFormation();
        $response=new Response();
       
        $formations_liste=[];
        foreach($formations as $formation)
        {
            $formations_liste[]=['tag'=>$formation->getTag(),'id'=>$formation->getId()];
        }

        $response->setContent(json_encode(
            [
                'formation'=>$formations_liste
            ]
            )); 
        
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}

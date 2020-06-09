<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Entreprise;
use App\Entity\Contact;
use App\Entity\Candidature;
use App\Entity\Formation;
use App\Entity\Formations;
use App\Service\ResumeCandidatures;

class AffichageController extends AbstractController
{
    /**
     * @Route("/affichage", name="affichage")
     */
    //retourner infos entreprise avec infos contacts/formations/candidatures liées
    public function affichageEntreprise(Request $request)
    {
        $idEntreprise=$request->get('id');
        $response=new Response;
        if($idEntreprise!=null)
        {
            //recup entreprise infos
            $entreprise=$this->getDoctrine()->getRepository(Entreprise::class)->find($idEntreprise);
            $infos_entreprise=[
                'id'=>$request->get('id'),
                'nom'=>$entreprise->getNom(),
                'secteur_activite'=>$entreprise->getSecteurActivite(),
                'code_postal'=>$entreprise->getCodePostal(),
                'ville'=>$entreprise->getVille(),
                'adresse'=>$entreprise->getAdresse(),
                'departement'=> $entreprise->getDepartement(),
                'tel'=> $entreprise->getTel(),
                'mail'=> $entreprise->getMail(),
                'historique'=> unserialize($entreprise->getHistoriqueModif())
            ];
            //recup contact infos
            $contacts=$entreprise->getContacts();
            $liste_contact=[];
            foreach($contacts as $contact)
            {
                $liste_contact[]=[
                    'id'=>$contact->getId(),
                    'nom'=>$contact->getNom(),
                    'fonction'=>$contact->getFonction(),
                    'tel'=>$contact->getTel(),
                    'mail'=>$contact->getMail(),
                    'linkedin'=>$contact->getLinkedin()
                ];
            }
            //recup formations
            $formations=$entreprise->getFormation();
            foreach($formations as $formation)
            {
               
                $candidatures_formation['reponse']['accept']= $this->getDoctrine()->getRepository(Candidature::class)->findByFormationReponseAccept($formation->getId(),$entreprise->getId());

                $candidatures_formation['reponse']['refus après entretien'] =  $this->getDoctrine()->getRepository(Candidature::class)->findByFormationRefuseApresEntrerien($formation->getId(),$entreprise->getId());

                $candidatures_formation['reponse']['refus sans entretien'] =  $this->getDoctrine()->getRepository(Candidature::class)->findByFormationRefuseSansEntrerien($formation->getId(),$entreprise->getId());

                $candidatures_formation['reponse']['en attente'] =  $this->getDoctrine()->getRepository(Candidature::class)->findByFormationReponseEnAttente($formation->getId(),$entreprise->getId());

                $candidatures_formation['reponse']['entretien en attente de reponse'] =  $this->getDoctrine()->getRepository(Candidature::class)->findByFormationReponseEntetien($formation->getId(),$entreprise->getId());

                $candidatures_formation['etat']['En cours'] =  $this->getDoctrine()->getRepository(Candidature::class)->findByFormationEtatEnCours($formation->getId(),$entreprise->getId());

                $candidatures_formation['moyen']['lettre'] =  $this->getDoctrine()->getRepository(Candidature::class)->findByFormationLettre($formation->getId(),$entreprise->getId());

                $candidatures_formation['moyen']['email'] =  $this->getDoctrine()->getRepository(Candidature::class)->findByFormationEmail($formation->getId(),$entreprise->getId());

                $candidatures_formation['moyen']['tel'] =  $this->getDoctrine()->getRepository(Candidature::class)->findByFormationTel($formation->getId(),$entreprise->getId());

                $candidatures_formation['moyen']['sur place'] =  $this->getDoctrine()->getRepository(Candidature::class)->findByFormationPlace($formation->getId(),$entreprise->getId());

                $nombre = $this->getDoctrine()->getRepository(Candidature::class)->findByFormationCandidature($formation->getId(),$entreprise->getId());

                $refus = $this->getDoctrine()->getRepository(Candidature::class)->findByFormationRefus($formation->getId(),$entreprise->getId());
                
                $liste_formation[]=[
                    'tag'=>$formation->getTag(),
                    'candidatures'=>$candidatures_formation,
                    'nombre'=>$nombre,
                    'refus'=>$refus
                ];
            }
            //recup candidature infos
            $candidatures=$entreprise->getCandidatures();
            $resumeCandidatures= new ResumeCandidatures;
            $liste_candidature=[];
            $moyens=[];
            $etats=[];
            $reponses=[];
            $formations_candidature=[];
            $delai_reponses=[];
            $nb_candidatures=0;

            foreach ($candidatures as $candidature)
            {
                $moyens[]=$candidature->getMoyen();
                $etats[]=$candidature->getEtat();
                $reponses[]=$candidature->getReponse();
                $delai_reponses[]=$candidature->getDelaiReponse();
                $nb_candidatures+=1;
                //regrouper moyen/etat/reponse par formation
                $formations_candidature[$candidature->getFormation()->getTag()][]=[
                    'moyen'=>$candidature->getMoyen(),
                    'etat'=>$candidature->getEtat(),
                    'reponse'=>$candidature->getReponse()
                ];
            }
            //resumé infos candidature moyens/etat/reponses/delai/
            $liste_moyens=$resumeCandidatures->listeMoyens($moyens);
            $liste_etats=$resumeCandidatures->listeEtats($etats);
            $liste_reponses=$resumeCandidatures->listeReponses($reponses);
            if(count($delai_reponses)!=0)
            {
                $delai_reponse=number_format(array_sum($delai_reponses) / (count($delai_reponses)),1);
            }
            else
            {
                 $delai_reponse='--';
            }
           //liste candidatures selon formation
            $liste_formations_candidature=[];
        
            foreach($formations_candidature as $key=>$formation)
            {
            $nb_candidatures2=0;
                foreach ($formation as $form)
                {
                    $moyens2[]=$form['moyen'];
                    $etats2[]=$form['etat'];
                    $reponses2[]=$form['reponse'];
                    $nb_candidatures2+=1;
                }
                $liste_moyens2=$resumeCandidatures->listeMoyens($moyens2);
                $liste_etats2=$resumeCandidatures->listeEtats($etats2);
                $liste_reponses2=$resumeCandidatures->listeReponses($reponses2);
                $liste_formations_candidature[]=[
                    'tag'=>$key,
                    'nb'=>$nb_candidatures2,
                    'moyen'=>$liste_moyens2,
                    'etat'=>$liste_etats2,
                    'reponse'=>$liste_reponses2,
                   
                ];
            }
            $liste_candidature[]=[
                'nb'=>$nb_candidatures,
                'moyen'=>$liste_moyens,
                'etat'=>$liste_etats,
                'reponse'=>$liste_reponses,
                'formation'=>$liste_formations_candidature,
                'delai_reponse'=>$delai_reponse,
            ];
            $response->setContent(json_encode(
                [ 
                    'entreprise_infos'=>$infos_entreprise,
                    'liste_contact'=>$liste_contact,
                    'liste_formation'=>$liste_formation,
                    'liste_candidature'=>$liste_candidature,
                    'formation'=>$liste_formations_candidature,
                ]
            )); 
        }
        else
        {
            $response->setContent(json_encode(
                [ 'id'=>'pas ok', ]
            )); 
        }
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}

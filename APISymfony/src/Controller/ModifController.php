<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Entreprise;
use App\Entity\User;
use App\Entity\Contact;
use App\Entity\Candidature;
use App\Entity\Formation;
use App\Form\AjoutEntrepriseType;
use App\Form\AjoutContactType;
use App\Form\AjoutCandidatureType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;



class ModifController extends AbstractController
{
    /**
     * @Route("/modif/entreprise", name="modifEntreprise")
     */
    //update entreprise
    public function entreprise(Request $request)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $entrepriseUpdate=new Entreprise();
        $response = new Response();
        //récupérer objet entreprise en bdd selon l'id
        $entrepriseUpdate=$this->getDoctrine()->getRepository(Entreprise::class)->find($request->get('id'));
        //faire un clone de l'entité pour pouvoir comparer modifs
        $entrepriseAvantUpdate= clone $entrepriseUpdate;
        //cretion formalaire lié à l'entité
        $form = $this->createForm(AjoutEntrepriseType::class, $entrepriseUpdate);
        //remove champs nom form
        $form->remove('nom');
        $values=$request->request->all();
        //remove data qui ne servent pas dans le formulaire
        unset($values["X-AUTH-TOKEN"]);
        unset($values['id']);
        unset($values['formation']);
        $errors = array();
        //récupe liste formation
        $formations=$request->get('formation');
        if($formations==null)
        {
            $errors['formation'][] = 'Veuillez saisir une formation';
        }
        //submit form avec values récupérées en post
        $form->submit($values); 
        $date=new \DateTime();
        $historique=unserialize($entrepriseUpdate->getHistoriqueModif());
        //si form valide
        if ($form->isValid()&&$formations!=null) 
        {
            //pour chaque attribut de l'entité qui peut avoir été modifié verif si il a changé ou pas
            foreach(['SecteurActivite', 'Adresse', 'CodePostal','Tel','Mail'] as $field)
            {
                //si il a changé ajouter la modif à l'historique
                if($propertyAccessor->getValue($entrepriseAvantUpdate, $field)!=$propertyAccessor->getValue($entrepriseUpdate, $field))
                {
                    $modif='modif entreprise :'.preg_replace('/(?=(?<!^)[[:upper:]])/', ' ', $field);
                    $historique[$date->format('Y-m-d H:i')][] =$modif ;
                }
            }
            //récup id formations liées à l'entité
            $formationsAvantUpdate=$entrepriseAvantUpdate->getFormation();
            foreach($formationsAvantUpdate as $form)
            {
                $formationsId[]=$form->getId();
            }
            //verif si une formation a été ajoutée ou supprimée
            foreach($formations as $formation)
            {
                if(!in_array($formation,$formationsId))
                {
                    $formationAdd=$this->getDoctrine()->getRepository(Formation::class)->find(intval($formation));
                    if($formationAdd!=null)
                    {
                        $entrepriseUpdate->addFormation($formationAdd);
                    }
                }
            }
             foreach($formationsId as $formation)
            {
                if(!in_array($formation,$formations))
                {
                    $formationAdd=$this->getDoctrine()->getRepository(Formation::class)->find(intval($formation));
                    if($formationAdd!=null)
                    {
                        $entrepriseUpdate->removeFormation($formationAdd);
                    }
                }
            }

            $entrepriseUpdate->setHistoriqueModif(serialize($historique));
            //enregistré update en bdd et retourner reponse
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $response->setContent(json_encode(
                [
                    'ajout'=>'ok'

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
                    'ajout'=>'pas ok',
                    'erreur'=>$errors
                ]
            )); 
        }
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/modif/contact", name="modifContact")
     */
    public function contact(Request $request)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $contactUpdate=new Contact();
        $response = new Response();
        //récupérer objet contact en bdd selon l'id
        $contactUpdate=$this->getDoctrine()->getRepository(Contact::class)->find($request->get('id'));
        //faire un clone de l'entité pour pouvoir comparer modifs
        $contactAvantUpdate= clone $contactUpdate;
        //cretion formalaire lié à l'entité
        $form = $this->createForm(AjoutContactType::class, $contactUpdate);
        //remove champs nom form
        //$form->remove('nom');
        $values=$request->request->all();
        //remove data qui ne servent pas dans le formulaire
        unset($values["X-AUTH-TOKEN"]);
        unset($values['id']);
        $errors = array();
        //submit form avec values récupérées en post
        $form->submit($values); 
        $date=new \DateTime();

        //si form valide
        if ($form->isValid()) 
        {
            //entreprise avant update
            $entreprise=$contactAvantUpdate->getEntreprise();
            $historique=unserialize($entreprise->getHistoriqueModif());
            /* si changement entreprise
            //entreprise après update
            $entrepriseUpdate=$this->getDoctrine()->getRepository(Entreprise::class)->find($request->get('entreprise'));
            
            if($entreprise->getId()!=$entrepriseUpdate->getId())
            {
                $historique2=unserialize($entrepriseUpdate->getHistoriqueModif());
                //historique entreprise qui gagne le contact
                $modif2='ajout contact :'.$contactUpdate->getNom().', '.$contactUpdate->getFonction();
                $historique2[$date->format('Y-m-d H:i')][] =$modif2 ;
                $entrepriseUpdate->setHistoriqueModif(serialize($historique2));
                //historique entreprise qui perd le contact
                $modif='suppression contact :'.$contactUpdate->getNom().', '.$contactUpdate->getFonction();
                $historique[$date->format('Y-m-d H:i')][] =$modif ;
                $contactUpdate->setEntreprise($entrepriseUpdate);
                $entreprise->setHistoriqueModif(serialize($historique));
            }
            
            else
            {*/
                //pour chaque attribut de l'entité qui peut avoir été modifié verif si il a changé ou pas
                foreach(['nom','fonction', 'tel', 'mail','linkedin','entreprise'] as $field)
                {
                //si il a changé ajouter la modif à l'historique
                    if($propertyAccessor->getValue($contactAvantUpdate, $field)!=$propertyAccessor->getValue($contactUpdate, $field))
                    {
                        $modif='modif contact '. $contactUpdate->getNom().', '.$contactAvantUpdate->getFonction() .':'.$field;
                        $historique[$date->format('Y-m-d H:i')][] =$modif ;
                    }
                //} 
                $entreprise->setHistoriqueModif(serialize($historique));
            }
            //enregistré update en bdd et retourner reponse
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $response->setContent(json_encode(
                [
                    'modif'=>'ok','id'=>$entreprise->getId()
                    
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
                    'ajout'=>'pas ok',
                    'erreur'=>$errors
                ]
            )); 
        }
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
    /**
     * @Route("/modif/candidature", name="modifCandidature")
     */
    public function candidature(Request $request)
     {
        $candidatureUpdate=new Candidature();
        $response = new Response();
        //récupérer objet contact en bdd selon l'id
        $candidatureUpdate=$this->getDoctrine()->getRepository(Candidature::class)->find($request->get('id'));
        //creation formulaire lié à l'entité
        $form = $this->createForm(AjoutCandidatureType::class, $candidatureUpdate);
        //remove champs nom form
        $form->remove('moyen');
        $form->remove('entreprise');
        $form->remove('formation');
        $form->remove('dateEnvoieCandidature');
        $values=$request->request->all();
        //remove data qui ne servent pas dans le formulaire
        unset($values["X-AUTH-TOKEN"]);
        unset($values['id']);
        $errors = array();
        //submit form avec values récupérées en post
        $form->submit($values); 
        
        if ($form->isValid()) 
        {
            //enregistré update en bdd et retourner reponse
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $response->setContent(json_encode(
                [
                    'modif'=>'ok',
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
                    'ajout'=>'pas ok',
                    'erreur'=>$errors
                ]
            )); 
        }
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}

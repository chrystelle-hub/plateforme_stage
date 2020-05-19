<?php
namespace App\Service;
class ResumeCandidatures
{
    function listeMoyens($moyens)
    {
        $moyens0=0;
        $moyens1=0;
        $moyens2=0;
        $moyens3=0;
        foreach($moyens as $moyen)
        {
            switch ($moyen)
            {
                case 0: $moyens0+=1;
                     break;
                case 1:$moyens1+=1;
                   break;
                case 2:$moyens2+=1;
                   break;
                case 3:$moyens3+=1;
                   break;
            }
        }
        return $liste_moyens=['lettre'=>$moyens0,'email'=>$moyens1,'telephone'=>$moyens2,'sur place'=>$moyens3];
    }
    function listeEtats($etats)
    {
        $etats0=0;
        $etats1=0;
        foreach($etats as $etat)
        {
            switch ($etat)
            {
                case 0: $etats0+=1;
                    break;
               case 1:$etats1+=1;
                    break;
            }
        }
        return $liste_etats=['encours'=>$etats0,'fini'=>$etats1];
    }
    function listeReponses($reponses)
    {
        $reponses0=0;
        $reponses1=0;
        $reponses2=0;
        $reponses3=0;
        $reponses4=0;
        foreach($reponses as $reponse)
        {
            switch ($reponse)
            {
                case 0: $reponses0+=1;
                    break;
                case 1:$reponses1+=1;
                    break;
                case 2:$reponses2+=1;
                    break;
                case 3:$reponses3+=1;
                    break;                       
                case 4:$reponses4+=1;
                    break;
            }
        }
        return $liste_reponses=['en attente'=>$reponses0,'entretien en attente de reponse'=>$reponses1,'refuse apres entretien'=>$reponses2,'refuse sans entretien'=>$reponses3,'accepte'=>$reponses4];
    }
}
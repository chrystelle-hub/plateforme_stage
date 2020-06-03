var url = location.search;
var param = url.substr(1);
param = param.split('&');
var tab = param[0].split('=');
var token = tab[1];
if (param[1] != undefined) {
  var tab2 = param[1].split('=');
  var id = tab2[1];
} else {
  id = null;
}

$(document).ready(function () {
  $.ajax({
    url: 'https://127.0.0.1:8000/testconnexion',
    type: 'POST',
    data: {
      'X-AUTH-TOKEN': token,
    },
    dataType: 'json',
    crossDomain: true,
  })
    .done(function (data) {
      if (data['login'] === 'ok') {
        $('#header').load('template2.html');
      } else {
        $('#header').load('template1.html');
        document.location.href = 'connexion.html?token=' + token;
      }
    })
    .fail(function () {
      $('#header').load('template1.html');
      document.location.href = 'connexion.html?token=' + token;
    })
    .always(function () {
      //url a changer
      $('#contenu').load('entrepriseVue.html', function () {
        $.ajax({
          url: 'https://127.0.0.1:8000/affichage',
          type: 'POST',
          data: {
            'X-AUTH-TOKEN': token,
            id: id,
          },
          dataType: 'json',
          crossDomain: true,
        })
          .done(function (data) {
            let entreprise_infos = data['entreprise_infos'];
            $('#add_candidature').click(function () {
              document.location.href =
                'ajout_candidature.html?token=' +
                token +
                '&id=' +
                entreprise_infos['id'];
            });
            $('#add_contact').click(function () {
              document.location.href =
                'ajout_contact.html?token=' +
                token +
                '&id=' +
                entreprise_infos['id'];
            });
            $('#modif_entreprise').click(function () {
              document.location.href =
                'modif_entreprise.html?token=' +
                token +
                '&id=' +
                entreprise_infos['id'];
            });

            $('#nomEntreprise').text(entreprise_infos['nom']);
            $('#adresse').html('<i class="icon icon-location"></i> '+
              'Adresse : ' +
                entreprise_infos['adresse'] +
                ' ' +
                entreprise_infos['code_postal'] +
                ' ' +
                entreprise_infos['ville'].toUpperCase()
            );
            if (entreprise_infos['mail'] == 0) {
              $('#mailEntreprise').html('<i class="icon icon-mail"></i> Mail : inconnu');
            } else {
              $('#mailEntreprise').html('<i class="icon icon-mail"></i> Mail : ' + entreprise_infos['mail']);
            }
            if (entreprise_infos['tel'] == 0) {
              $('#telephoneEntreprise').html('<i class="icon icon-phone"></i> T\351l\351phone :inconnu');
            } else {
              $('#telephoneEntreprise').html(
                '<i class="icon icon-phone"></i> T\351l\351phone : ' + entreprise_infos['tel']
              );
            }
            $('#secteur').html('<i class="fas fa-briefcase"></i> Secteur d\'activit\351 : ' + entreprise_infos['secteur_activite']);
            $('#departement').text(entreprise_infos['departement']);
            let candidatures = data['liste_candidature'][0];
            $('#CandidatureLettre').text(candidatures['moyen']['lettre']);
            $('#CandidatureEmail').text(candidatures['moyen']['email']);
            $('#CandidatureTelephone').text(candidatures['moyen']['telephone']);
            $('#CandidaturePlace').text(candidatures['moyen']['sur place']);
            $('#Candidature1').text(candidatures['reponse']['en attente']);
            $('#Candidature2').text(
              candidatures['reponse']['entretien en attente de reponse']
            );
            $('#Candidature3').text(
              candidatures['reponse']['refuse apres entretien']
            );
            $('#Candidature4').text(
              candidatures['reponse']['refuse sans entretien']
            );
            $('#Candidature5').text(candidatures['reponse']['accepte']);
            let taux;
            if (
              candidatures['reponse']['refuse apres entretien'] +
                candidatures['reponse']['refuse sans entretien'] !=
              0
            ) {
              taux =
                (candidatures['reponse']['accepte'] /
                  (candidatures['reponse']['accepte'] +
                    candidatures['reponse']['refuse apres entretien'] +
                    candidatures['reponse']['refuse sans entretien'])) *
                100;
            } else {
              if (candidatures['reponse']['accepte'] === 0) {
                taux = 0;
              } else {
                taux = 100;
              }
            }
            $('#nb').text(candidatures['nb']);
            $('#taux').text(taux + '%');
            $('#delai').text(candidatures['delai_reponse']);
            $('#accept').text(candidatures['reponse']['accepte']);
            $('#attente').text(candidatures['etat']['encours']);
            $('#refus').text(
              candidatures['reponse']['refuse apres entretien'] +
                candidatures['reponse']['refuse sans entretien']
            );
            let contacts = data['liste_contact'];
            contacts.forEach(function (element) {
              if (element['linkedin'] != null) {
                linkedin = element['linkedin'];
              } else {
                linkedin = 'inconnu';
              }
              if (element['mail'] != null) {
                mail = element['mail'];
              } else {
                mail = 'inconnu';
              }
              if (element['tel'] != null) {
                tel = element['tel'];
              } else {
                tel = 'inconnu';
              }
              let url =
                'modif_contact.html?token=' + token + '&id=' + element['id'];
              let html =
                '<div id="contenair_contact"  class="ligne_contact dylan_js">' +
                '<div  class="name_ligne_contact">' +
                '<h4>' +
                element['nom'] +
                '</h4>' +
                '<label>' +
                element['fonction'] +
                '</label>' +
                '<i class="fas fa-location-arrow"></i>' +
                '</div>' +
                '<div id="modif" class="info_ligne_contact cache_info" >' +
                '<div class="icone_user">' +
                '<ul>' +
                '<li><i class="icon icon-linkedin-squared"></i></li>' +
                '<li><i class="icon icon-mobile"></i></li>' +
                '<li><i class="icon icon-mail"></i></li>' +
                '</ul>' +
                '</div>' +
                '<div class="icone_text">' +
                '<ul>' +
                '<li><a href="https://fr.linkedin.com/">' +
                linkedin +
                '</a></li>' +
                '<li>' +
                tel +
                '</li>' +
                '<li>' +
                mail +
                '</li>' +
                '</ul>' +
                '<a href=' +
                url +
                '><button class="btn btn-info">Modifier</button></a>' +
                '</div>' +
                '</div>' +
                '</div>';
              $(html).appendTo($('#contact'));
            });
            let candidatures_formation =
              data['liste_candidature'][0]['formation'];
            candidatures_formation.forEach(function (element) {
              let refus =
                element['reponse']['refuse apres entretien'] +
                element['reponse']['refuse sans entretien'];
              let html2 =
                '<div id="contenair_contact"  class="ligne_contact dylan_js">' +
                '<div  class="name_ligne_contact">' +
                '<p>' +
                element['tag'] +
                '</p>' +
                '<i class="fas fa-location-arrow"></i>' +
                '</div>' +
                '<div id="modif" class="info_ligne_contact cache_info" style="display: flex; flex-direction: column;">' +
                '<div class="stat_contenaire">' +
                '<div class="stat_1">' +
                '<div class="stat_classique">' +
                '<p>' +
                element['nb'] +
                '</p>' +
                '</div>' +
                '<label>Candidatures</label>' +
                '</div>' +
                '<div class="stat_1">' +
                '<div class="stat_classique">' +
                '<p>' +
                element['etat']['encours'] +
                '</p>' +
                '</div>' +
                '<label>En cours</label>' +
                '</div>' +
                '<div class="stat_1">' +
                '<div class="stat_classique">' +
                '<p>' +
                element['reponse']['accepte'] +
                '</p>' +
                '</div>' +
                '<label>Accept\351es</label>' +
                '</div>' +
                '<div class="stat_1">' +
                '<div class="stat_classique">' +
                '<p>' +
                refus +
                '</p>' +
                '</div>' +
                '<label>Refus\351es</label>' +
                '</div>' +
                '</div><br><hr>' +
                '<div id="modif" class="info_ligne_contact" >' +
                '<div class="icone_user">' +
                '<ul>' +
                '<li>Lettre</li>' +
                '<li>Email</li>' +
                '<li>Telephone</li>' +
                '<li>sur place</li>' +
                '</ul>' +
                '</div>' +
                '<div class="icone_text">' +
                '<ul>' +
                '<li id="CandidatureLettre">' +
                element['moyen']['lettre'] +
                '</li>' +
                '<li id="CandidatureEmail">' +
                element['moyen']['email'] +
                '</li>' +
                '<li id="CandidatureTelephone">' +
                element['moyen']['telephone'] +
                '</li>' +
                '<li id="CandidaturePlace">' +
                element['moyen']['sur place'] +
                '</li>' +
                '</ul>' +
                '</div>' +
                '</div>' +
                '<div><div id="modif" class="info_ligne_contact" >' +
                '<div class="icone_user">' +
                '<ul>' +
                '<li>En attente</li>' +
                '<li>Entretien en attente de r\351ponse</li>' +
                '<li>Refus apr\350s entretien</li>' +
                '<li>Refus sans entretien</li>' +
                '<li>Accept\351</li>' +
                '</ul>' +
                '</div>' +
                '<div class="icone_text">' +
                '<ul>' +
                '<li id="Candidature1">' +
                element['reponse']['en attente'] +
                '</li>' +
                '<li id="Candidature2">' +
                element['reponse']['entretien en attente de reponse'] +
                '</li>' +
                '<li id="Candidature3">' +
                element['reponse']['refuse apres entretien'] +
                '</li>' +
                '<li id="Candidature4">' +
                element['reponse']['refuse sans entretien'] +
                '</li>' +
                '<li id="Candidature5">' +
                element['reponse']['accepte'] +
                '</li>' +
                '</ul>' +
                '</div></div>' +
                '</div>' +
                '</div>' +
                '</div>';

              $(html2).appendTo($('#candidatureFormation'));
            });
            let formations = data['liste_formation'];
            formations.forEach(function (element) {
              let html3 = '<p>' + element['tag'] + '</p>';
              $(html3).appendTo($('#listeFormations'));
            });
            let historique = Object.entries(entreprise_infos['historique']);
            historique.forEach(function (element) {
              var date = new Date(element[0]);
              const options = {
                year: 'numeric',
                month: 'numeric',
                day: 'numeric',
              };
              var date = date.toLocaleDateString('fr-FR', options);

              let html4 = '<p>' + date + ' : ' + element[1] + '</p>';
              $(html4).appendTo($('#historique'));
            });
          })
          .fail(function () {
            $('#contenu').html(
              '<div class="contenair_relative"><h2>La page demand\351e n\'existe pas <h2></div>'
            );
          });
      });
      //$("#footer").load("footer.html");
    });
});

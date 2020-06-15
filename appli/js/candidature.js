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
      $.ajax({
        url: 'https://127.0.0.1:8000/recuperation/data/candidature',
        type: 'POST',
        data: {
          'X-AUTH-TOKEN': token,
          id: id,
        },
        dataType: 'json',
        crossDomain: true,
      }).done(function (data) {
        var date = new Date(data['candidature'].date.date);
        const options = {
          weekday: 'long',
          year: 'numeric',
          month: 'long',
          day: 'numeric',
        };
        var date = date.toLocaleDateString('fr-FR', options);

        if (data['candidature'].etat === 0) {
          var etat = 'En cours';
        } else {
          var etat = 'fini';
        }

        if (data['candidature'].moyen === "0") {
          var moyen = 'lettre';
        } else if (data['candidature'].moyen === "1") {
          var moyen = 'email';
        } else if (data['candidature'].moyen === "2") {
          var moyen = 't\351l\351phone';
        } else {
          var moyen = 'sur place';
        }

        if (data['candidature'].reponse === 0) {
          var reponse = 'en attente';
        } else if (data['candidature'].reponse === 1) {
          var reponse = 'entretien en attente de r\351ponse';
        } else if (data['candidature'].reponse === 2) {
          var reponse = 'refus\351 apr\350s entretien';
        } else if (data['candidature'].reponse === 3) {
          var reponse = 'refus\351 sans entretien';
        } else {
          var reponse = 'accept\351';
        }

        if (data['candidature'].delai_reponse === null) {
          var delai = 'non renseign\351';
        } else {
          var delai = data['candidature'].delai_reponse;
        }

        $('#nom_entreprise_candidature').text(data['candidature'].entreprise);
        $('#date_candidature').text(date);
        $('#etat_candidature').text(etat);
        $('#moyen_candidature').text(moyen);
        $('#delai_candidature').text(delai);
        $('#reponse_candidature').text(reponse);
      });

      $('#modif_candidature').click(function () {
        document.location.href =
          'modif_candidature.html?token=' + token + '&id=' + id;
      });

      $('#footer').load('footer.html');
    });
});

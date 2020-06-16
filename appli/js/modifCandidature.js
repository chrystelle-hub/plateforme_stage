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
  if (id === null) {
    document.location.href = 'profil.html?token=' + token;
  }
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
      $.ajax({
        url: 'https://127.0.0.1:8000/recuperation/data/candidature',
        type: 'POST',
        data: {
          'X-AUTH-TOKEN': token,
          id: id,
        },
        dataType: 'json',
        crossDomain: true,
      })
        .done(function (data) {
          var candidature = data['candidature'];
          const options = { year: 'numeric', month: 'long', day: 'numeric' };
          var date = new Date(data['candidature']['date']['date']);
          $('#infos').text(
            'Candidature chez ' +
              candidature['entreprise'] +
              ' le ' +
              date.toLocaleDateString('fr-FR', options)
          );
          $('#etat').val(candidature['etat']);
          $('#reponse').val(candidature['reponse']);
          $('#delai').val(candidature['delai_reponse']);
        })
        .fail(function () {
          $('#contenu').html(
            '<div class="contenair_relative"><h2>La page demand\351e n\'existe pas <h2></div>'
          );
        });
      $('#buttonModif').click(function () {
        $(document).ajaxStart(function () {
          $('#fountainG').css('display', 'block');
          $('#modif').css('display', 'none');
        });
        $(document).ajaxComplete(function () {
          $('#fountainG').css('display', 'none');
          $('#modif').css('display', 'block');
        });
        $.ajax({
          url: 'https://127.0.0.1:8000/modif/candidature',

          type: 'POST',
          data: {
            'X-AUTH-TOKEN': token,
            etat: $('#etat').val(),
            reponse: $('#reponse').val(),
            delai_reponse: $('#delai').val(),

            id: id,
          },
          dataType: 'json',
          crossDomain: true,
        }).done(function (data) {
          $('#erreurEtat').text('');
          $('#erreurReponse').text('');
          $('#erreurDelai').text('');
          if (data['modif'] === 'ok') {
            document.location.href =
              'candidature.html?token=' + token + '&id=' + id;
          } else {
            if (data['erreur']['etat']) {
              $('#erreurEtat').addClass('alert alert-danger');
              $('#erreurEtat').text(data['erreur']['etat'][0]);
            }
            if (data['erreur']['reponse']) {
              $('#erreurReponse').addClass('alert alert-danger');
              $('#erreurReponse').text(data['erreur']['reponse'][0]);
            }
            if (data['erreur']['delai_reponse']) {
              $('#erreurDelai').addClass('alert alert-danger');
              $('#erreurDelai').text(data['erreur']['delai_reponse'][0]);
            }
          }
        });
      });

      $("#footer").load("footer.html");
    });
});

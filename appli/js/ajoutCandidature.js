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
        url: 'https://127.0.0.1:8000/recuperation/data/formationuser',
        type: 'POST',
        dataType: 'json',
        data: {
          'X-AUTH-TOKEN': token,
        },
        crossDomain: true,
      }).done(function (data) {
        var formations = data['formation'];
        formations.forEach(function (element) {
          var option =
            '<option value=' +
            element['id'] +
            '> ' +
            element['tag'] +
            '</option>';
          $(option).appendTo($('#formation'));
        });
      });
      $('#buttonAjout').click(function () {
        console.log($('#date').val());
        $.ajax({
          url: 'https://127.0.0.1:8000/add/candidature',

          type: 'POST',
          data: {
            'X-AUTH-TOKEN': token,
            moyen: $('#mode').val(),
            etat: $('#etat').val(),
            reponse: $('#reponse').val(),
            delai_reponse: $('#delai').val(),
            entreprise: id,
            formation: $('#formation').val(),
            dateEnvoieCandidature: $('#date').val(),
          },
          dataType: 'json',
          crossDomain: true,
        }).done(function (data) {
          $('#erreurMode').text('');
          $('#erreurEtat').text('');
          $('#erreurReponse').text('');
          $('#erreurDelai').text('');
          $('#erreurFormation').text('');
          $('#erreurDate').text('');
          if (data['ajout'] === 'ok') {
            document.location.href = 'profil.html?token=' + token;
          } else {
            if (data['erreur']['moyen']) {
              $('#erreurMode').addClass('alert alert-danger');
              $('#erreurMode').text(data['erreur']['moyen'][0]);
            }
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
            if (data['erreur']['formation']) {
              $('#erreurFormation').addClass('alert alert-danger');
              $('#erreurFormation').text(data['erreur']['formation'][0]);
            }
            if (data['erreur']['dateEnvoieCandidature']) {
              $('#erreurDate').addClass('alert alert-danger');
              $('#erreurDate').text(data['erreur']['dateEnvoieCandidature'][0]);
            }
          }
        });
      });

     // $('#footer').load('footer.html');
    });
});

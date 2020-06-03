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
        $.ajax({
          url: 'https://127.0.0.1:8000/add/entreprise',

          type: 'POST',
          data: {
            'X-AUTH-TOKEN': token,
            nom: $('#name').val(),
            secteur_activite: $('#activite').val(),
            adresse: $('#adresse').val(),
            code_postal: $('#code_postal').val(),
            ville: $('#ville').val(),
            tel: $('#tel').val(),
            mail: $('#email').val(),
            formation: $('#formation').val(),
          },
          dataType: 'json',
          crossDomain: true,
        }).done(function (data) {
          $('#erreurNom').text('');
          $('#erreurFormation').text('');
          $('#erreurSecteur').text('');
          $('#erreurAdresse').text('');
          $('#erreurCode').text('');
          $('#erreurVille').text('');
          $('#erreurTel').text('');
          $('#erreurEmail').text('');
          if (data['ajout'] === 'ok') {
            document.location.href =
              'entreprise.html?token=' + token + '&id=' + data['id'];
          } else {
            if (data['erreur']['nom']) {
              $('#erreurNom').addClass('alert alert-danger');
              $('#erreurNom').text(data['erreur']['nom'][0]);
            }
            if (data['erreur']['formation']) {
              $('#erreurFormation').addClass('alert alert-danger');
              $('#erreurFormation').text(data['erreur']['formation'][0]);
            }
            if (data['erreur']['secteur_activite']) {
              $('#erreurSecteur').addClass('alert alert-danger');
              $('#erreurSecteur').text(data['erreur']['secteur_activite'][0]);
            }
            if (data['erreur']['adresse']) {
              $('#erreurAdresse').addClass('alert alert-danger');
              $('#erreurAdresse').text(data['erreur']['adresse'][0]);
            }
            if (data['erreur']['code_postal']) {
              $('#erreurCode').addClass('alert alert-danger');
              $('#erreurCode').text(data['erreur']['code_postal'][0]);
            }
            if (data['erreur']['ville']) {
              $('#erreurCode').addClass('alert alert-danger');
              $('#erreurCode').text(data['erreur']['ville'][0]);
            }
            if (data['erreur']['tel']) {
              $('#erreurTel').addClass('alert alert-danger');
              $('#erreurTel').text(data['erreur']['tel'][0]);
            }
            if (data['erreur']['mail']) {
              $('#erreurEmail').addClass('alert alert-danger');
              $('#erreurEmail').text(data['erreur']['mail'][0]);
            }
          }
        });
      });

      //$("#footer").load("footer.html");
    });
});

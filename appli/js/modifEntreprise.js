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
      $.ajax({
        url: 'https://127.0.0.1:8000/recuperation/data/formationactuelles',
        type: 'POST',
        dataType: 'json',
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
        $.ajax({
          url: 'https://127.0.0.1:8000/recuperation/data/entreprise',
          type: 'POST',
          data: {
            'X-AUTH-TOKEN': token,
            id: id,
          },
          dataType: 'json',
          crossDomain: true,
        })
          .done(function (data) {
            var entreprise = data['entreprise'];
            $('#nom').text(
              "Modification de  l'entreprise : " + entreprise['nom']
            );
            $('#activite').val(entreprise['secteur_activite']);
            $('#adresse').val(entreprise['adresse']);
            $('#code_postal').val(entreprise['code_postal']);
            $('#tel').val(entreprise['tel']);
            $('#email').val(entreprise['mail']);
            var formations = entreprise['formation'];
            formations.forEach(function (element) {
              $('#formation option[value=' + element + ']').attr(
                'selected',
                true
              );
            });
          })
          .fail(function () {
            $('#contenu').html(
              '<div class="contenair_relative"><h2>La page demand\351e n\'existe pas <h2></div>'
            );
          });
      });

      $('#buttonModif').click(function () {
        $.ajax({
          url: 'https://127.0.0.1:8000/modif/entreprise',

          type: 'POST',
          data: {
            'X-AUTH-TOKEN': token,
            nom: $('#name').val(),
            secteur_activite: $('#activite').val(),
            adresse: $('#adresse').val(),
            code_postal: $('#code_postal').val(),
            tel: $('#tel').val(),
            mail: $('#email').val(),
            formation: $('#formation').val(),
            id: id,
          },
          dataType: 'json',
          crossDomain: true,
        }).done(function (data) {
          $('#erreurFormation').text('');
          $('#erreurSecteur').text('');
          $('#erreurAdresse').text('');
          $('#erreurCode').text('');
          $('#erreurTel').text('');
          $('#erreurEmail').text('');
          if (data['ajout'] === 'ok') {
            document.location.href =
              'entreprise.html?token=' + token + '&id=' + id;
          } else {
            if (data['erreur']['formation']) {
              $('#erreurFormation').text(data['erreur']['formation'][0]);
            }
            if (data['erreur']['secteur_activite']) {
              $('#erreurSecteur').text(data['erreur']['secteur_activite'][0]);
            }
            if (data['erreur']['adresse']) {
              $('#erreurAdresse').text(data['erreur']['adresse'][0]);
            }
            if (data['erreur']['code_postal']) {
              $('#erreurCode').text(data['erreur']['code_postal'][0]);
            }
            if (data['erreur']['tel']) {
              $('#erreurTel').text(data['erreur']['tel'][0]);
            }
            if (data['erreur']['mail']) {
              $('#erreurEmail').text(data['erreur']['mail'][0]);
            }
          }
        });
      });

      $('#footer').load('footer.html');
    });
});

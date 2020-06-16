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
      $('#buttonAjout').click(function () {
        $(document).ajaxStart(function () {
          $('#fountainG').css('display', 'block');
          $('#modif').css('display', 'none');
        });
        $(document).ajaxComplete(function () {
          $('#fountainG').css('display', 'none');
          $('#modif').css('display', 'block');
        });
        $.ajax({
          url: 'https://127.0.0.1:8000/add/contact',

          type: 'POST',
          data: {
            'X-AUTH-TOKEN': token,
            nom: $('#name_contact').val(),
            fonction: $('#fonction_contact').val(),
            tel: $('#tel_contact').val(),
            mail: $('#email_contact').val(),
            linkedin: $('#linkedin_contact').val(),
            entreprise: id,
          },
          dataType: 'json',
          crossDomain: true,
        }).done(function (data) {
          $('#erreurEmail').text('');
          $('#erreurFonction').text('');
          $('#erreurNom').text('');
          $('#erreurLinkedin').text('');
          $('#erreurTel').text('');
          if (data['ajout'] === 'ok') {
            document.location.href =
              'entreprise.html?token=' + token + '&id=' + id;
          } else {
            if (data['erreur']['email']) {
              $('#erreurEmail').addClass('alert alert-danger');
              $('#erreurEmail').text(data['erreur']['email'][0]);
            }
            if (data['erreur']['nom']) {
              $('#erreurNom').addClass('alert alert-danger');
              $('#erreurNom').text(data['erreur']['nom'][0]);
            }
            if (data['erreur']['fonction']) {
              $('#erreurFonction').addClass('alert alert-danger');
              $('#erreurFonction').text(data['erreur']['fonction'][0]);
            }
            if (data['erreur']['linkedin']) {
              $('#erreurLinkedin').addClass('alert alert-danger');
              $('#erreurLinkedin').text(data['erreur']['linkedin'][0]);
            }
            if (data['erreur']['tel']) {
              $('#erreurTel').addClass('alert alert-danger');
              $('#erreurTel').text(data['erreur']['tel'][0]);
            }
          }
        });
      });

      $("#footer").load("footer.html");
    });
});

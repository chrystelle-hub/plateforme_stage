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
    document.location.href = 'profilfinal.html?token=' + token;
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
        url: 'https://127.0.0.1:8000/recuperation/data/contact',
        type: 'POST',
        data: {
          'X-AUTH-TOKEN': token,
          id: id,
        },
        dataType: 'json',
        crossDomain: true,
      })
        .done(function (data) {
          var contact = data['contact'];
          $('#nom').text('Modification du contact : ' + contact['nom']);
          $('#nom_contact').val(contact['nom']);
          $('#fonction_contact').val(contact['fonction']);
          $('#tel_contact').val(contact['tel']);
          $('#email_contact').val(contact['mail']);
          $('#linkedin_contact').val(contact['linkedin']);
        })
        .fail(function () {
          $('#contenu').html(
            '<div class="contenair_relative"><h2>La page demand\351e n\'existe pas <h2></div>'
          );
        });
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
          url: 'https://127.0.0.1:8000/modif/contact',

          type: 'POST',
          data: {
            'X-AUTH-TOKEN': token,
            id: id,
            nom:$('#nom_contact').val(),
            fonction: $('#fonction_contact').val(),
            tel: $('#tel_contact').val(),
            mail: $('#email_contact').val(),
            linkedin: $('#linkedin_contact').val(),
            entreprise: id,
          },
          dataType: 'json',
          crossDomain: true,
        }).done(function (data) {
          $('#erreurNom').text('')
          $('#erreurEmail').text('');
          $('#erreurFonction').text('');
          $('#erreurLinkedin').text('');
          $('#erreurTel').text('');
          if (data['modif'] === 'ok') {
            document.location.href =
              'entreprise.html?token=' + token + '&id=' + data['id'];
          } else {
            if (data['erreur']['nom']) {
              $('#erreurEmail').addClass('alert alert-danger');
              $('#erreurEmail').text(data['erreur']['nom'][0]);
            }
            if (data['erreur']['email']) {
              $('#erreurEmail').addClass('alert alert-danger');
              $('#erreurEmail').text(data['erreur']['email'][0]);
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

      $('#footer').load('footer.html');
    });
});

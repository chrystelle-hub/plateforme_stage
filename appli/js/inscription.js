var url = location.search;
var param = url.substr(1);
var tab = param.split('=');
var token = tab[1];

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
        document.location.href = 'profilfinal.html?token=' + token;
      } else {
        //$( "#header" ).load( "template1.html" );
      }
    })
    .fail(function () {
      //$( "#header" ).load( "template1.html" );
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
          $(option).appendTo($('select'));
        });

        $('#buttonInscription').click(function () {
          $.ajax({
            url: 'https://127.0.0.1:8000/register',

            type: 'POST',
            data: {
              email: $('#email').val(),
              password: $('#password').val(),
              nom: $('#nom').val(),
              prenom: $('#prenom').val(),
              formation: $('#formation').val(),
            },
            dataType: 'json',
            crossDomain: true,
          }).done(function (data) {
            $('#erreurEmail').text('');
            $('#erreurNom').text('');
            $('#erreurPrenom').text('');
            $('#erreurPassword').text('');
            if (data['inscription'] === 'ok') {
              $('#message').addClass('alert alert-info');
              $('#message').text(
                'inscription valide, en attente de validation par un formateur'
              );
            } else {
              if (data['erreur']['email']) {
                showAlert(
                  '#erreurEmail',
                  data['erreur']['email'][0],
                  'alert alert-danger'
                );
              }
              if (data['erreur']['nom']) {
                showAlert(
                  '#erreurNom',
                  data['erreur']['nom'][0],
                  'alert alert-danger'
                );
              }
              if (data['erreur']['prenom']) {
                showAlert(
                  '#erreurPrenom',
                  data['erreur']['prenom'][0],
                  'alert alert-danger'
                );
              }
              if (data['erreur']['password']) {
                showAlert(
                  '#erreurPassword',
                  data['erreur']['password'][0],
                  'alert alert-danger'
                );
              }
            }
            console.log(data);
          });
        });
      });

      $("#footer").load("footer.html");
    });
});

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
        // $( "#header" ).load( "template2.html" );
        document.location.href = 'profilfinal.html?token=' + token;
      } else {
        //$( "#header" ).load( "template1.html" );
      }
    })
    .fail(function () {
      //$( "#header" ).load( "template1.html" );
    })
    .always(function () {
      $('#seconnecter').click(function () {
        $(document).ajaxStart(function () {
          $('#chargement').css('display', 'block');
        });
        $(document).ajaxComplete(function () {
          $('#chargement').css('display', 'none');
        });
        $.ajax({
          url: 'https://127.0.0.1:8000/login',
          type: 'POST',
          data: {
            email: $('#email').val(),
            password: $('#password').val(),
          },
          dataType: 'json',
          crossDomain: true,
        }).done(function (data) {
          if (data['login'] != false) {
            //redirection vers page profil
            var token = data['login'];
            document.location.href = 'profil.html?token=' + token;
          } else {
            if (data['message'] === 'activation') {
              showAlert(
                '#erreur',
                "Votre compte n'est pas encore activ\351",
                'alert alert-danger'
              );
            } else {
              showAlert(
                '#erreur',
                'Identifiants incorrects',
                'alert alert-danger'
              );
            }
          }
          console.log(data);
        });
      });

      $("#footer").load("footer.html");
    });
});

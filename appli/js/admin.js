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
        url: 'https://127.0.0.1:8000/admin/list/user',

        //headers:{"X-AUTH-TOKEN":"REAL"},
        data: {
          'X-AUTH-TOKEN': token,
        },
        type: 'POST',
        dataType: 'json',
        crossDomain: true,
      })
        .done(function (data) {
          data.forEach(function (data) {
            if (data['roles'][0] == 'ROLE_USER') {
              var html =
                '<div class="liste_formateur">' +
                '<div class="nom_form">' +
                '<label>Nom Prenom</label>' +
                '<p id="user_nom_prenom">' +
                data['nom'] +
                ' ' +
                data['prenom'] +
                '</p>' +
                '</div>' +
                '<button class="buttonFormateur">' +
                '<input type="hidden" value=' +
                data['id'] +
                '>' +
                'Formateur</button>' +
                '</div>';
              $(html).appendTo($('.formateur_align'));
            }
          });
          $('.buttonFormateur').click(function () {
            var ajout_role = $(event.target);
            var id_user_role = ajout_role.children('input').val();

            $.ajax({
              url: 'https://127.0.0.1:8000/admin/add/role',

              //headers:{"X-AUTH-TOKEN":"o8d4bxtlKq8Qd7IQCcaKwpV3Jjseywranq9UPF0ZF9uZBAA7Ku"},
              data: {
                'X-AUTH-TOKEN': token,
                id: id_user_role,
              },
              type: 'POST',
              dataType: 'json',
              crossDomain: true,
            })
              .done(function (data) {
                document.location.href = 'admin.html?token=' + token;
                //console.log(data);
                //alert(JSON.parse(data));
                //alert(data);
              })
              .always(function () {
                console.log('end2');
              });
          });

          //console.log(data);
          //alert(JSON.parse(data));
          //alert(data);
        })
        .always(function () {
          console.log('end2');
        });

      $.ajax({
        url: 'https://127.0.0.1:8000/admin/formation/users',

        //headers:{"X-AUTH-TOKEN":"REAL"},
        data: {
          'X-AUTH-TOKEN': token,
        },
        type: 'POST',
        dataType: 'json',
        crossDomain: true,
      })
        .done(function (data) {
          var users = data['users'];

          users.forEach(function (element) {
            if (
              element['etat_compte'] === 0 &&
              element['roles'][0] == 'ROLE_USER'
            ) {
              var date = new Date(element['date_creation_password'].date);
              const options = {
                year: 'numeric',
                month: 'numeric',
                day: 'numeric',
              };
              var date = date.toLocaleDateString('fr-FR', options);

              var html =
                '<div class="user">' +
                '<div>' +
                '<label>Nom Prenom</label>' +
                '<p id="nom_prenom">' +
                element['nom'] +
                ' ' +
                element['prenom'] +
                '</p>' +
                '</div>' +
                '<div>' +
                '<label>date inscription</label>' +
                '<p id="date_creation">' +
                date +
                '</p>' +
                '</div>' +
                '<div class="div_accept">' +
                '<button class="valide">' +
                '<input type="hidden" value=' +
                element['id'] +
                '>' +
                'Valider</button>' +
                '</div>' +
                '</div>';
              $(html).appendTo($('.user_activ'));
            }
            if (
              element['etat_compte'] === 1 &&
              element['roles'][0] == 'ROLE_USER'
            ) {
              var candidatures = element['candidatures'];

              var date = new Date(element['date_creation_password'].date);
              const options = {
                year: 'numeric',
                month: 'numeric',
                day: 'numeric',
              };
              var date = date.toLocaleDateString('fr-FR', options);

              var stage = element['stage'];
              if (stage === 1) {
                var stage = 'Oui';
              }
              if (stage === 0) {
                var stage = 'Non';
              }
              var html =
                '<div class="user">' +
                '<div>' +
                '<label>Nom Prenom</label>' +
                '<p id="nom_prenom">' +
                element['nom'] +
                ' ' +
                element['prenom'] +
                '</p>' +
                '</div>' +
                '<div>' +
                '<label>date inscription</label>' +
                '<p id="date_creation">' +
                date +
                '</p>' +
                '</div>' +
                '<div>' +
                '<label>nombre candidatures</label>' +
                '<p id="date_creation">' +
                element['nombre_candidature'] +
                '</p>' +
                '</div>' +
                '<div>' +
                '<label>Stage</label>' +
                '<p id="date_creation">' +
                stage +
                '</p>' +
                '</div>' +
                '<div>' +
                '<label>Formation</label>' +
                '<p id="date_creation">' +
                element['formation'] +
                '</p>' +
                '</div>' +
                /*'<div class="div_accept">' +
                '<button id="desactive" class="desactive">' +
                '<input type="hidden" value=' +
                element['id'] +
                '>' +
                'D\351sactiver</button>' +
                '</div>' +*/
                '</div>';
        
              $(html).appendTo($('.user_activ2'));

            
            }
          });

          $('.valide').click(function () {
            var validation = $(event.target);
            var id_user = validation.children('input').val();

            $.ajax({
              url: 'https://127.0.0.1:8000/admin/validate',

              //headers:{"X-AUTH-TOKEN":"o8d4bxtlKq8Qd7IQCcaKwpV3Jjseywranq9UPF0ZF9uZBAA7Ku"},
              data: {
                'X-AUTH-TOKEN': token,
                id: id_user,
              },
              type: 'POST',
              dataType: 'json',
              crossDomain: true,
            })
              .done(function (data) {
                if (data['status'] === 200) {
                  $('#success').text('le compte est valid\351');
                  document.location.href = 'admin.html?token=' + token;
                } else {
                  $('#success').text("le compte n'est pas valid\351");
                }

                
              })
              .always(function () {
                console.log('end2');
              });
          });

          $('#desactive').click(function () {
            var validation = $(event.target);
            var id_user = validation.children('input').val();

            $.ajax({
              url: 'https://127.0.0.1:8000/admin/desactive',

              //headers:{"X-AUTH-TOKEN":"o8d4bxtlKq8Qd7IQCcaKwpV3Jjseywranq9UPF0ZF9uZBAA7Ku"},
              data: {
                'X-AUTH-TOKEN': token,
                id: id_user,
              },
              type: 'POST',
              dataType: 'json',
              crossDomain: true,
            })
              .done(function (data) {
                if (data['status'] === 200) {
                  $('#success').text('le compte est desactiv\351');
                  document.location.href = 'admin.html?token=' + token;
                } else {
                  $('#success').text("le compte n'est pas desactiv\351");
                }

              })
              .always(function () {
                console.log('end2');
              });
          });

          console.log(data);
        })
        .always(function () {
          console.log('end2');
        });

      $('#ajout_formation').click(function () {
        $.ajax({
          url: 'https://127.0.0.1:8000/admin/add/formation',

          //headers:{"X-AUTH-TOKEN":"o8d4bxtlKq8Qd7IQCcaKwpV3Jjseywranq9UPF0ZF9uZBAA7Ku"},
          data: {
            'X-AUTH-TOKEN': token,
            Nom: $('#nom_formation').val(),
            promotion: $('#promotion').val(),
            Tag: $('#nom_formation').val() + '/' + $('#promotion').val(),
          },
          type: 'POST',
          dataType: 'json',
          crossDomain: true,
        })
          .done(function (data) {
            if (data['ajout'] == 'pas ok') {
              $('#ajout').text("la formation n'est pas ajout\351e");
            } else {
              $('#ajout').text('la formation est ajout\351e');
              document.location.href = 'admin.html?token=' + token;
            }

          })
          .always(function () {
            console.log('end2');
          });
      });

      $('#search').click(function () {
        $.ajax({
          url: 'https://127.0.0.1:8000/admin/search',

          //headers:{"X-AUTH-TOKEN":"o8d4bxtlKq8Qd7IQCcaKwpV3Jjseywranq9UPF0ZF9uZBAA7Ku"},
          data: {
            'X-AUTH-TOKEN': token,
            email: $('#search_email').val(),
          },
          type: 'POST',
          dataType: 'json',
          crossDomain: true,
        })
          .done(function (data) {
            var user = data['user'];

            if (user['roles'][0] == 'ROLE_USER') {
              var html =
                '<div class="liste_formateur">' +
                '<div class="nom_form">' +
                '<label>Nom Prenom</label>' +
                '<p id="user_nom_prenom">' +
                user['nom'] +
                ' ' +
                user['prenom'] +
                '</p>' +
                '</div>' +
                '<button class="buttonFormateur">' +
                '<input type="hidden" value=' +
                user['id'] +
                '>' +
                'Formateur</button>' +
                '</div>';
              $('.formateur_align').replaceWith(html);
            }

            $('.buttonFormateur').click(function () {
              var ajout_role = $(event.target);
              var id_user_role = ajout_role.children('input').val();

              $.ajax({
                url: 'https://127.0.0.1:8000/admin/add/role',

                //headers:{"X-AUTH-TOKEN":"o8d4bxtlKq8Qd7IQCcaKwpV3Jjseywranq9UPF0ZF9uZBAA7Ku"},
                data: {
                  'X-AUTH-TOKEN': token,
                  id: id_user_role,
                },
                type: 'POST',
                dataType: 'json',
                crossDomain: true,
              })
                .done(function (data) {
                  document.location.href = 'admin.html?token=' + token;
     
                })
                .always(function () {
                  console.log('end2');
                });
            });
          
          })
          .always(function () {
            console.log('end2');
          });
      });

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

        $('#inscrire').click(function () {
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
              $('#message').text(
                'inscription valide, en attente de validation par un formateur'
              );
              document.location.href = 'admin.html?token=' + token;
            } else {
              if (data['erreur']['email']) {
                $('#erreurEmail').text(data['erreur']['email'][0]);
              }
              if (data['erreur']['nom']) {
                $('#erreurNom').text(data['erreur']['nom'][0]);
              }
              if (data['erreur']['prenom']) {
                $('#erreurPrenom').text(data['erreur']['prenom'][0]);
              }
              if (data['erreur']['password']) {
                $('#erreurPassword').text(data['erreur']['password'][0]);
              }
            }
            console.log(data);
          });
        });
      });

      $("#footer").load("footer.html");
    });
});

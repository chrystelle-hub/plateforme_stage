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
        url: 'https://127.0.0.1:8000/profil/data',
        type: 'POST',
        data: {
          'X-AUTH-TOKEN': token,
        },
        dataType: 'json',
        crossDomain: true,
      }).done(function (data) {
        $('#nomModif').val(data['nom']);
        $('#prenomModif').val(data['prenom']);
        $('#stageModif').val(data['stage']);
      });
      $('#edit').click(function () {
        $.ajax({
          url: 'https://127.0.0.1:8000/profil/modif',
          type: 'POST',
          data: {
            'X-AUTH-TOKEN': token,
            nom: $('#nomModif').val(),
            prenom: $('#prenomModif').val(),
            password: $('#pwdModif').val(),
            stage: $('#stageModif').val(),
          },
          dataType: 'json',
          crossDomain: true,
        }).done(function (data) {
          //console.log($('#stageModif').val());
          if (data['modif'] === 'ok') {
            document.location.href = 'profil.html?token=' + token;
          } else {
          }
        });
      });

      var admin = false;
      $.ajax({
        url: 'https://127.0.0.1:8000/testRole',
        type: 'POST',
        data: {
          'X-AUTH-TOKEN': token,
        },
        dataType: 'json',
        crossDomain: true,
      })
        .done(function (data) {
          if (data['connexion']) {
            let url = 'admin.html?token=' + token;
            var html =
              '<button class="btn btn-lg btn-info" id="role_admin" style="margin:10px;"><a href=' +
              url +
              ' style="text-decoration: none;color:white;">Tableau de bord formateur</a></button>';
            $('.historique').html(html);
            admin = true;
          }

          $.ajax({
            url: 'https://127.0.0.1:8000/profil',

            //headers:{"X-AUTH-TOKEN":"REAL"},
            data: {
              'X-AUTH-TOKEN': token,
            },
            type: 'POST',
            dataType: 'json',
            crossDomain: true,
          }).done(function (data) {
            $('#nom').append(data.user[0].nom);
            $('#prenom').append(data.user[0].prenom);
            $('#prenom_utilisateur').append(data.user[0].prenom);
            $('#email').append(data.user[0].email);

            var role = data.user[0].role[0];

            if (role !== 'ROLE_ADMIN') {
              $('#liStage').css('display', 'block');
              $('#stage').css('display', 'block');
              $('#stageModif').css('display', 'inline');
            }

            if (data.user[0].stage === 0) {
              $('#stage').append('Non');
            }

            if (data.user[0].stage === 1) {
              $('#stage').append('Oui');
            }

            if (role === 'ROLE_ADMIN') {
              $('#statut').append('Formateur');
            }

            if (role === 'ROLE_USER') {
              $('#statut').append('Stagiaire');
            }

            $('#formation').append(data.user[0].formation[0]);
            //console.log(data);

            if (admin === false) {
              var candidature = data['candidature'];
              var html2 =
                '<h2>Historique de mes candidatures</h2>' +
                '<div class="historique_contenair">' +
                '</div>';
              $('.historique').html(html2);

              candidature.forEach(function (element) {
                var date = new Date(element['date'].date);
                const options = {
                  weekday: 'long',
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric',
                };
                var date = date.toLocaleDateString('fr-FR', options);
                var etat = element['etat'];

                if (element['etat'] === 0) {
                  var etat = 'En cours';
                }

                if (element['etat'] === 1) {
                  var etat = 'Fini';
                }
                if (etat === 'Fini') {
                  var iconeClass = 'fas fa-circle vert';
                } else {
                  var iconeClass = 'fas fa-circle orange';
                }

                var html =
                  '<a style="color:black; text-decoration:none;" href="candidature.html?token=' +
                  token +
                  '&id=' +
                  element['id'] +
                  '"><div class="ligne_histo">' +
                  '<div class="nom_histo">' +
                  '<label>Nom entreprise</label>' +
                  '<h3 id="nom_entreprise">' +
                  element['entreprise'] +
                  '</h3>' +
                  '</div>' +
                  '<div class="date_histo">' +
                  '<label>Date</label>' +
                  '<h3 id="date_candidature">' +
                  date +
                  '</h3>' +
                  '</div>' +
                  '<div class="statut_histo">' +
                  '<div>' +
                  '<label>Statut</label>' +
                  '<h3 id="etat_candidature">' +
                  etat +
                  '</h3>' +
                  '</div>' +
                  '<i class="' +
                  iconeClass +
                  '"></i>' +
                  '</div>' +
                  '</div></a>';

                $(html).appendTo($('.historique_contenair'));
              });
            }

            //alert(JSON.parse(data));
            //alert(data);
          });
        })
        .always(function () {
          console.log('end2');
        });

      //bouton deconnexion

      $('#deconnexion').click(function () {
        $.ajax({
          url: 'https://127.0.0.1:8000/deconnexion',
          type: 'POST',
          data: {
            'X-AUTH-TOKEN': token,
          },
          dataType: 'json',
          crossDomain: true,
        })
          .done(function (data) {
            console.log(data);
          })
          .always(function () {
            document.location.href = 'connexion.html';
          });
      });

      $("#footer").load("footer.html");
    });
});

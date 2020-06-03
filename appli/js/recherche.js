var url = location.search;
var param = url.substr(1);
param = param.split('&');
var tab = param[0].split('=');
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
      var url = 'ajout_entreprise.html?token=' + token;
      $('#ajout').attr('href', url);
      $('#buttonSearch').click(function () {
        $(document).ajaxStart(function(){
				
          $('#chargement_rechercher').css('display','block')
          
        
        })
        $(document).ajaxComplete(function(){
          
          $('#chargement_rechercher').css('display','none')
          
        })
        $.ajax({
          url: 'https://127.0.0.1:8000/search',
          type: 'POST',
          data: {
            'X-AUTH-TOKEN': token,
            nom: $('#nom').val(),
            departement: $('#departement').val(),
            secteur_activite: $('#secteur').val(),
            formation: $('#formation').val(),
          },
          dataType: 'json',
          crossDomain: true,
        }).done(function (data) {
          if (data['erreur'] === undefined) {
            $('#erreur').text('');
            if (data['entreprise'].length === 0) {
              $('.contenair_entreprise').addClass('alert alert-danger mt-2');
              $('.contenair_entreprise').text(
                'Aucune entreprise ne correspond \340 la recherche'
              );

              // Remove alert after 3s
              setTimeout(() => {
                $('.contenair_entreprise').removeClass(
                  'alert alert-danger mt-2'
                );
                $('.contenair_entreprise').text('');
              }, 3000);

              // Clear input fields
              document.getElementById('nom').value = '';
              document.getElementById('formation').value = '';
              document.getElementById('secteur').value = '';
              document.getElementById('departement').value = '';
            } else {
              $('.contenair_entreprise').html('');

              var entreprise = data['entreprise'];
              entreprise.forEach(function (element) {
                var tags = element['tag'];
                var tag = 'Formations: <br>';
                tags.forEach(function (element) {
                  tag += element + '<br>';
                });
                var html =
                  '<div class="entreprise">' +
                  '<div class="name_entreprise">' +
                  '<h3>' +
                  element['nom'] +
                  '</h3>' +
                  '<i class="fas fa-circle-notch"></i>' +
                  '</div>' +
                  '<div class="papa">' +
                  '<p class="connexion_formulaire"><i class="fas fa-angle-double-down"></i></p>' +
                  '</div>' +
                  '<div class="stat_contenair">' +
                  '<div class="contenair_stat_entre">' +
                  '<div class="stat_entreprise">' +
                  '<i class="fas fa-globe"></i>' +
                  '</div>' +
                  '<a>'+element['ville']+'</a>' +
                  '</div>' +
                  '<div class="contenair_stat_entre">' +
                  '<div class="stat_entreprise">' +
                  '<i class="fas fa-briefcase"></i>' +
                  '</div>' +
                  '<a>' +
                  element['secteur_activite'] +
                  '</a>' +
                  '</div>' +
                  '<div class="contenair_stat_entre">' +
                  '<div class="stat_entreprise">' +
                  '<i class="fas fa-map-marked-alt"></i>' +
                  '</div>' +
                  '<a>' +
                  element['code_postal'] +
                  '</a>' +
                  '</div>' +
                  '</div>' +
                  '<div class="desc_entreprise">' +
                  '<div class="description_contenair">' +
                  '<p>' +
                  tag +
                  '</p>' +
                  '</div>' +
                  '</div>' +
                  '<a href="entreprise.html?token=' +
                  token +
                  '&id=' +
                  element['id'] +
                  '"><button class="btn btn-success mb-2" style="background-color:#87bb34;border-color:#87bb34;"> Voir la fiche compl\350te </button></a>' +
                  '</div>';
                $(html).appendTo($('.contenair_entreprise'));

                // Clear input fields
                document.getElementById('nom').value = '';
                document.getElementById('formation').value = '';
                document.getElementById('secteur').value = '';
                document.getElementById('departement').value = '';
              });
            }
          } else {
            $('#erreur').text(data['erreur']['departement'][0]);
          }
          console.log(data);
        });
      });

      //$("#footer").load("footer.html");
    });
});

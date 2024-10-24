// Import jQuery
import $ from 'jquery';

// Import Popper.js
import { createPopper } from '@popperjs/core';

// Import Bootstrap
import 'bootstrap';

/*
* Script pour la vérification de l'enregistrement de l'utilisateur
*/

// Initialize Bootstrap dropdowns
$(function() {
    $('#register-user').on('click', function() {
       var Username = $('#Username').val();
       if (Username != ""&&  /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/.test(Username))
        {
            $('#Username').removeClass('is-invalid');
            $('#Username').addClass('is-valid');
            $('#error-register-Username').text("")
        }else
        {
            $('#Username').removeClass('is-valid');
            $('#Username').addClass('is-invalid');
            $('#error-register-Username').text("le nom d'utilisateur n'est pas valide!")
        }
       var Email = $('#Email').val();
       if (Email != ""&&  /^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/.test(Email))
        {
            $('#Email').removeClass('is-invalid');
            $('#Email').addClass('is-valid');
            $('#error-register-Email').text("")
        }else
        {
            $('#Email').removeClass('is-valid');
            $('#Email').addClass('is-invalid');
            $('#error-register-Email').text("l'Email n'est pas valide!")
        }
       var Password = $('#Password').val();

       var PasswordConfirmation = $('#PasswordConfirmation').val();
       var PasswordLength = Password.length;
       if (PasswordConfirmation >= 8)
        {
            $('#Password').removeClass('is-invalid');
            $('#Password').addClass('is-valid');
            $('#error-register-Password').text("")
        }else
        {
            $('#Password').removeClass('is-valid');
            $('#Password').addClass('is-invalid');
            $('#error-register-Password').text("le mot de passe n'est pas valide!")
        }
        if (PasswordConfirmation == Password)
            {
                $('#PasswordConfirmation').removeClass('is-invalid');
                $('#PasswordConfirmation').addClass('is-valid');
                $('#error-register-PasswordConfirmation').text("")
            }else
            {
                $('#PasswordConfirmation').removeClass('is-valid');
                $('#PasswordConfirmation').addClass('is-invalid');
                $('#error-register-PasswordConfirmation').text("les deux mots de passe ne sont pas identique!")
            }
        var terms =$('#terms');
        if (terms.is(':checked '))
        {
            $('#terms').addRemove('is-invalid');
            $('#error-register-terms').text("")
            var res = emailExistJs(Email);
            (res!="exist") ? $('#form-register').submit(): $ ('#Email').addClass('is-invalid');$('#Email').removeClass('is-valid');$('#error-register-Email').text("cette adresse e-mail est deja utiliser!");

        }else
        {
            $('#terms').addClass('is-invalid');
            $('#error-register-terms').text("vous devez accepter les termes et conditions d'utilisation!")
        }
        ///envoie du formulaire
           $('#form-register').submit();
        //Evenement pour l'input term et conditions
        $('#terms').change(function() {
            var terms = $('#terms')
            if (terms.is(':checked '))
                {
                    $('#terms').addRemove('is-invalid');
                    $('#error-register-terms').text("")
                }else
                {
                    $('#terms').addClass('is-invalid');
                    $('#error-register-terms').text("vous devez accepter les termes et conditions d'utilisation!")
                }
        });
    });
});
function emailExistJs(Email){
    var url = $('#Email').attr('url-existEmail');
    var token = $('#Email').attr('token');
    var response ="";
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            Email: Email, '_token': token},
        success: function(result){

            response = result.response;
        },
        async:false
    });
    return response;
}

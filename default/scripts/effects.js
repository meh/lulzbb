/* lulzBB *****************
 * Author : cHoBi
 * |- MSN  : chobi@unlogical.net
 * `- eMail: chobi.noob@gmail.com
 *
 * License: GPLv3
 *************************/

function register(option) {
    switch(option) {
        case 'username':
            username = document.getElementById('username').value;
            
            POST(
                'username-error',
                '?input&register&check&username',
                'username='+rawurlencode(username)
            );
        break;

        case 'email':
            email              = document.getElementById('email').value;
            email_confirmation = document.getElementById('email-confirmation').value;

            if (email_confirmation != '' || (email == '' && email_confirmation == '')) {
                POST(
                    'email-error',
                    '?input&register&check&email',
                    'email1='+rawurlencode(email)+'&email2='+rawurlencode(email_confirmation)
                );
            }
        break;

        case 'password':
            password              = document.getElementById('password').value;
            password_confirmation = document.getElementById('password-confirmation').value;
            
            if (password_confirmation != '' || (password == '' && password_confirmation == '')) {
                POST(
                    'password-error',
                    '?input&register&check&password',
                    'password1='+rawurlencode(password)+'&password2='+rawurlencode(password_confirmation)
                );
            }
        break;

        case 'register':
            username = document.getElementById('username').value;
            email    = document.getElementById('email').value;
            password = document.getElementById('password').value;

            username_error = document.getElementById('username-error');
            email_error    = document.getElementById('email-error');
            password_error = document.getElementById('password-error');

            if (username_error.innerHTML == 'Ok.' && email_error.innerHTML == 'Ok.' && (password.length >= 6 && password.length <= 30)) {
                POST('middle', '?input&register&send', 'username='+rawurlencode(username)+'&email='+rawurlencode(email)+'&password='+rawurlencode(password));
            }

            else {
                if (username_error.innerHTML != 'Ok.') {
                    register('username');
                }
                if (email_error.innerHTML != 'Ok.') {
                    register('email');
                }
                if (password_error.innerHTML != 'Ok.') {
                    register('password');
                }
                
                document.getElementById('error').innerHTML
                    = "<b>You're doing it wrong, Sir.</b>";

                return false;
            }
        break;
    }       
}

function hide(id) {
    document.getElementById(id).style.display = 'none';
}

function unhide(id) {
    document.getElementById(id).style.display = 'block';
}

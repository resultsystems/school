/**
 * Helpers
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
function angularScope(e) {
    return angular.element($('[ng-controller="' + e + '"]')).scope();
}

function dd() {
    if (angular.element(document.body).injector().get('APP').debug) {
        console.log((arguments.length > 1) ? arguments : arguments[0]);
    }
}

function showErrors(errors) {
    if (typeof errors == 'string') {
        return flash.error(errors);
    }
    var error = '';

    if (typeof errors == 'object') {
        for (var k in errors) {
            error = error + k + ': ' + errors[k] + "\n";
        }
        return flash.error(error);
    }

    for (var i = errors.length - 1; i >= 0; i--) {
        error = error + errors[i] + "\n";
    };

    return flash.error(error);
}

/**
 * Formats numbers
 * https://github.com/the-darc/string-mask
 * 
 * npm install --save string-mask
 * bower install --save string-mask
 *
 * @param  {string} data
 * @return {string}
 */
function formats(data){
    if (data===undefined) {
        return '';
    }

    var value=data.toString().trim().replace(/[^0-9]$/, '');

    return {
        cnpjCpf: function() {
            if (value.length <= 11) {
                return this.cpf(value);
            }

            return this.cnpj(value);
        },
        cnpj: function() {
            var formatter = new StringMask('00.000.000\/0000-00');

            return formatter.apply(value);
        },
        cpf: function() {
            var formatter = new StringMask('000.000.000-00');

            return formatter.apply(value);
        },
        currency: function() {
            var formatter = new StringMask('#.##0,00', {reverse: true});

            return 'R$ '+formatter.apply(value*100);
        },
        postcode: function() {
            var formatter = new StringMask('00.000-000');

            return formatter.apply(value);
        },
        phone: function() {
            if (value.indexOf('0300')>-1 && value.length==11) {
                var formatter = new StringMask('0000-000-0000');
            } else if (value.indexOf('0500')>-1 && value.length==11) {
                var formatter = new StringMask('0000-000-0000');
            } else if (value.indexOf('0800')>-1 && value.length==11) {
                var formatter = new StringMask('0000-000-0000');
            } else if (value.indexOf('0800')>-1 && value.length==12) {
                var formatter = new StringMask('0000-0000-0000');
            } else if (value.length==8) {
                var formatter = new StringMask('0000-0000');
            } else if (value.length==9) {
                var formatter = new StringMask('0 0000-0000');
            } else if (value.length==10) {
                var formatter = new StringMask('00 0000-0000');
            } else if (value.length==11) {
                var formatter = new StringMask('00 0 0000-0000');
            }

            return formatter.apply(value);
        }
    }
}

function clearDigits(value)
{
    if (value==undefined) {
        return;
    }
    return value.toString().trim().replace(/[^0-9]$/, '');
}

function isSettled(value )
{
    if (value) {
        return moment(value).format('DD/MM/YYYY')
    }

    return 'não';
}

function paid(value)
{
    if (value) {
        return 'Pago';
    }

    return 'A pagar';
}

function yesNo(value)
{
    if (value) {
        return 'Sim';
    }

    return 'Não';
}

function getUser()
{
    var data=window.localStorage.getItem('user');
    if (data==null) {
        return;
    }
    return JSON.parse(data);
}

function setUser(user)
{
    window.localStorage.setItem('user', JSON.stringify(user));
}

function getToken()
{
    var data=window.localStorage.getItem('token');
    if (data==null) {
        return;
    }
    return JSON.parse(data);
}

function setToken(token)
{
    window.localStorage.setItem('token', JSON.stringify(token));
}

function delToken()
{
    window.localStorage.removeItem('user');
    window.localStorage.removeItem('token');
}
/**
 * Postcode Module/Service
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
angular.module('Postcode', []).service('Postcode', ['$http',
    function($http) {
        return {
            get: function(cep, success, error) {
                if (cep==undefined || cep=="") {
                    return;
                }
                var aux = cep.replace(/[^0-9]+/g, "");
                if (aux == undefined || aux == ""  || aux.length != 8) {
                    return;
                }

                $http.get('https://viacep.com.br/ws/' + aux + '/json/').then(function(response) {
                    if (response.data.erro == undefined) {
                        return success(response.data);
                    }
                    if (typeof error != "undefined") {
                        error(response.data);
                    }
                }, function(response) {
                    if (typeof error != "undefined") {
                        error(response.data);
                    }
                });
            }
        };
    }
]);
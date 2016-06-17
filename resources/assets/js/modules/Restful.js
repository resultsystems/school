/**
 * Restful Module/Service
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
angular.module('Restful', []).service('Restful', ['$rootScope', '$http', 'API', Restful]);

function Restful($rootScope, $http, API) {
    return {
        /**
         * Get path with student/teacher/employee
         */
        getPath: function(mod)
        {
            var module = mod ? mod : '';

            var owner='';

            if (typeof $rootScope.user == 'undefined') {
                $rootScope.user=getUser();
            }
            if (typeof $rootScope.user.owner_type == 'undefined') {
                return;
            }
            if ($rootScope.user.owner_type=='Domain\\Student\\Student') {
                owner='student/';
            }
            if ($rootScope.user.owner_type=='Domain\\Teacher\\Teacher') {
                owner='teacher/';
            }

            return API.path+owner+API.version+module;
        },

        /**
         * get headers
         */
        getHeaders: function() {
            return {
                'headers': {
                    'Authorization': 'Bearer ' + getToken()
                }
            };
        },

        /**
         * Adiciona um novo item
         *
         * @param  {string} path
         * @param  {object} data
         * @param  {function} success
         * @param  {function} error
         * @param  {object} headers
         * @param  {function} afterRequest
         *
         * @return success|error
         */
        post: function(path, data, success, error, headers, afterRequest) {
            var config = headers ? headers : this.getHeaders();
            dd(config);
            $http.post(this.getPath(path), data, config).then(function(response) {
                dd(response);
                if (afterRequest != undefined && typeof afterRequest == "function") {
                    afterRequest(response);
                }

                if (success != undefined && typeof success == "function") {
                    success(response.data);
                }
            }, function(response) {
                dd(response);

                if (afterRequest != undefined && typeof afterRequest == "function") {
                    afterRequest(response);
                }

                //Verifica se foi falha de permissão
                if (unauthenticated(response.data)) {
                    return;
                }

                if (error != undefined && typeof error == "function") {
                    error(response);
                }
            });
        },

        /**
         * Altera um item
         *
         * @param  {string} path
         * @param  {object} data
         * @param  {function} success
         * @param  {function} error
         * @param  {object} headers
         * @param  {function} afterRequest
         *
         * @return success|error
         */
        put: function(path, data, success, error, headers, afterRequest) {
            var config = headers ? headers : this.getHeaders();
            $http.put(this.getPath(path), data, config).then(function(response) {
                dd(response);
                if (afterRequest != undefined && typeof afterRequest == "function") {
                    afterRequest(response);
                }

                if (success != undefined && typeof success == "function") {
                    success(response.data);
                }
            }, function(response) {
                dd(response);

                if (afterRequest != undefined && typeof afterRequest == "function") {
                    afterRequest(response);
                }

                //Verifica se foi falha de permissão
                if (unauthenticated(response.data)) {
                    return;
                }

                if (error != undefined && typeof error == "function") {
                    error(response);
                }
            });
        },

        /**
         * store or put
         * 
         * @param  {string} path
         * @param  {object} data
         * @param  {function} success
         * @param  {function} error
         * @param  {object} headers
         * @param  {function} afterRequest
         *
         * @return success|error
         */
        save: function(path, data, success, error, headers, afterRequest) {
            if (data.id!=undefined) {
                return this.put(path+'/'+data.id, data, success, error, headers, afterRequest);
            }
            
            return this.post(path, data, success, error, headers, afterRequest);
        },

        /**
         * Apaga um item
         *
         * @param  {string} path
         * @param  {function} success
         * @param  {function} error
         * @param  {object} headers
         * @param  {string} name
         *
         * @return success|error
         */
        delete: function(path, success, error, headers, name) {
            name = name || '';
            var that=this;
            flash.confirm(function() {
                var config = headers ? headers :  that.getHeaders();
                $http.delete(that.getPath(path), config).then(function(response) {
                    if (success != undefined && typeof success == "function") {
                        success(response.data);
                    }
                }, function(response) {
                    //Verifica se foi falha de permissão
                    if (unauthenticated(response.data)) {
                        return;
                    }

                    if (error != undefined && typeof error == "function") {
                        error(response);
                    }
                });
            }, 'Continuar na exclusão de: ' + name, 'Exclusão!', 'Sim, continuar', 'Cancelar');
        },
        remove: function(path, data, success, error, headers) {
            return this.delete(path+'/'+data.id, success, error, headers, data.name);
        },

        /**
         * Pega um ou mais itens
         *
         * @param  {string} path
         * @param  {function} success
         * @param  {function} error
         * @param  {object} headers
         *
         * @return success|error
         */
        get: function(path, success, error, headers) {
            var config = headers ? headers : this.getHeaders();
            $http.get(this.getPath(path), config).then(function(response) {
                if (typeof success == "function") {
                    success(response.data);
                }
            }, function(response) {
                //Verifica se foi falha de permissão
                if (unauthenticated(response.data)) {
                    return;
                }

                if (error != undefined && typeof error == "function") {
                    error(response);
                }
            });
        },

        /**
         * Altera senha
         *
         * @param  {string} path
         * @param  {object} data
         * @param  {function} success
         * @param  {function} error
         * @param  {object} headers
         *
         * @return success|error
         */
        changePassword: function(path, data, success, headers) {
            if (data.password == undefined || data.newPassword == undefined || data.newPassword_confirmation == undefined || data.password == '' || data.newPassword == '' || data.newPassword_confirmation == '') {
                dd(data);

                return flash.warning('Preencha todos os campos', 'Aviso!');
            }

            if (data.newPassword != data.newPassword_confirmation) {
                dd(data);

                return flash.warning('A confirmação da senha não coincide com a nova senha', 'Aviso!');
            }

            var config = headers ? headers : this.getHeaders();
            $http.put(this.getPath(path), data, config).then(function(response) {
                success(response.data);
                flash.success("Senha alterada com sucesso!", 'Sucesso!');
            }, function(response) {
                //Verifica se foi falha de permissão
                if (unauthenticated(response.data)) {
                    return;
                }
                dd(response.data);

                return flash.error('Houve uma falha ao tentar alterar a senha', 'Erro!');
            });
        }
    }

    /**
     * Verifica se o erro é de autenticação
     *
     * @param  {object}  error
     *
     * @return {boolean}
     */
    function unauthenticated(error) {
        //Array com as strings que são erros de permissões ou não está logado
        $rootScope.noRedirect=false;
        var noAuthenticated = ['user_not_found', 'token_absent', 'token_expired', 'token_not_provided', 'invalid_credentials', 'token_invalid'];

        if (error == undefined || error.error == undefined) {
            return false;
        }

        if ($.inArray(error.error, noAuthenticated) > -1) {
            $rootScope.noRedirect=true;
            $rootScope.showAuth();

            return true;
        }
        return false;
    }
}

/**
 * Auth Service
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
angular.module('SchoolApp').service('Auth', ['$rootScope', '$http', 'APP', 'API',
    function($rootScope, $http, APP, API) {
        return {
            login: function(data, success, error) {
                 window.localStorage.setItem('type_login', data.type);
                $http.post(API.path+API.version+'auth/login', data).then(function(response) {
                    $rootScope.modalLogin=false;
                    $rootScope.token=response.data.token;
                    $rootScope.user=response.data.user;

                    setUser(response.data.user);
                    setToken(response.data.token);

                    if (typeof success=='function') {
                        return success(response.data);
                    }
                }, function(response) {
                    delToken();
                    if (typeof error=='function') {
                        return error(response);
                    }
                    showErrors(response.data);
                });
            },
            logout: function(success, error)
            {
                var headers={
                    'headers': {
                        'Authorization': 'Bearer ' + $rootScope.token
                    }
                };
                $http.get(API.path+API.version+'auth/logout', headers).then(function(response) {
                    delToken();

                    $rootScope.showAuth();
                    $rootScope.token='';
                    $rootScope.user='';
                    if (typeof success=='function') {
                        return success(response.data);
                    }
                }, function(response) {
                    if (typeof error=='function') {
                        return error(response);
                    }
                    showErrors(response.data);
                });
            }
        };
    }
]);
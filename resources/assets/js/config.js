SchoolApp.controller('AppController', ['$rootScope', '$scope', '$filter', '$location', 'Auth', 'OrderService', function($rootScope, $scope, $filter, $location, Auth, OrderService) {
    $rootScope.user=getUser();
    $rootScope.token=getToken();
    $scope.modalLogin=$rootScope.token==null || $rootScope.user==null;
    $scope.auth={
        'type':  window.localStorage.getItem('type_login')
    };

    /**
     * Ordena objetos
     *
     * @param  {array} data
     * @param  {string} name
     * @param  {string} property
     */
    $rootScope.order = function(data, name, property) {
        var order = OrderService.get(name, property);

        return $filter('orderBy')(data, order);
    };

    $rootScope.orderSimple=function(data, name)
    {
        return $filter('orderBy')(data, name);
    }

    /**
     * ascDescOrder
     */
    $rootScope.byOrder = function(name, property) {
        return OrderService.byOrder(name, property);
    };

    $scope.login=function(login)
    {
        Auth.login(login, function(data) {
                $scope.modalLogin=false;
                $scope.auth={
                    'type':  window.localStorage.getItem('type_login')
                };
                dd('no redirect');
                dd($rootScope.noRedirect);
                if (!$rootScope.noRedirect) {
                     $location.path('/#');
                }
        });
    }

    $rootScope.showAuth=function()
    {
        $scope.modalLogin=true;
    }
}]);

SchoolApp.controller('HeaderController', ['$rootScope', '$scope', 'Auth', 'API', function($rootScope, $scope, Auth, API) {
    $rootScope.logoUrl='/logo.jpg?'+moment().format('MMYYYYh:mm:ss');

    $scope.logout=function()
    {
        Auth.logout(function(data)
            {
                $rootScope.showAuth();
                $rootScope.token=undefined;
                $rootScope.user=undefined;
            });
    }
}]);


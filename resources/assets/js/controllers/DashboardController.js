'use strict';

/**
 * Dashboard controller
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
SchoolApp.controller('DashboardController', ['$rootScope', '$scope', 'Restful', function($rootScope, $scope, Restful) {
    var path='dashboard';

    $scope.dashboard={};

    if ($rootScope.user!=undefined){
        Restful.get(path, function(data) {
            $scope.dashboard=data;
        });
    }
}]);
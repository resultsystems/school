'use strict';
/**
 * Billet Assignor controller
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
SchoolApp.controller('BilletAssignorController', ['$rootScope', '$scope', 'Restful', 'StatesAndCities', 'Upload', 'API', function($rootScope, $scope, Restful, StatesAndCities, Upload, API) {
    var path='billet/assignor';

    $scope.assignor={'is_interest':'true'};
    $scope.banks=[
        { 'name': 'bb', 'title': 'Banco do brasil' },
        { 'name': 'bradesco', 'title': 'Bradesco' },
        { 'name': 'caixa', 'title': 'Caixa Econômica Federal' },
        { 'name': 'hsbc', 'title': 'HSBC' },
        { 'name': 'itau', 'title': 'Itaú' },
        { 'name': 'santander', 'title': 'Santander' }
    ];

    $scope.states=StatesAndCities.getStates();
    $scope.cities=[];

    $scope.setState=function(state)
    {
        $scope.assignor.city=undefined;
        StatesAndCities.setState(state);
        $scope.cities=StatesAndCities.getCities(state);
        setTimeout(function() {
            $("#assignorCity").select2().trigger('change');
        }, 100);
    }

    Restful.get(path+'/first', function(data) {
        $scope.assignor=prepareAssignor(data);

        StatesAndCities.setState(data.state);
        $scope.cities=StatesAndCities.getCities(data.state);
        setTimeout(function() {
            $("#assignorState").select2().trigger('change');
            $("#assignorBank").select2().trigger('change');
            $scope.assignor.city=data.city;
            setTimeout(function() {
                $("#assignorCity").select2().trigger('change');
            }, 100);
        }, 200);
    });

    $scope.save=function(data)
    {
        var assignor=angular.copy(data);
        if (assignor.is_interest=='1' || assignor.is_interest=='true' || assignor.is_interest===true) {
            assignor.is_interest=true;
        } else {
            assignor.is_interest=false;
        }
        if (assignor.acceptance=='1' || assignor.acceptance=='true' || assignor.acceptance===true) {
            assignor.acceptance=true;
        } else {
            assignor.acceptance=false;
        }

        assignor.cnpj=clearDigits(assignor.cnpj);
        assignor.postcode=clearDigits(assignor.postcode);

        if ($scope.assignorForm.logo.$valid && $scope.logo) {
            assignor.logo=$scope.logo;
            return $scope.upload(assignor);
        }

        Restful.post(path, assignor, function(data) {
            flash.success('Salvo!');
        }, function(response) {
            showErrors(response.data);
            dd(response.data);
        });
    }

    // upload on file select or drop
    $scope.upload = function (assignor) {
        Upload.upload({
            url: Restful.getPath(path),
            data: assignor,
            headers:  {
                'Authorization': 'Bearer ' + getToken()
            }
        }).then(function (resp) {
            $rootScope.logoUrl='/logo.jpg?'+moment().format('MMYYYYh:mm:ss');
            flash.success('Salvo!');
        }, function (resp) {
            showErrors(resp.data);
            dd(resp.data);
        });
    };

    var prepareAssignor=function(assignor)
    {
        if (assignor.is_interest=='1' || assignor.is_interest=='true' || assignor.is_interest===true) {
            assignor.is_interest=true;
        } else {
            assignor.is_interest=false;
        }
        if (assignor.acceptance=='1' || assignor.acceptance=='true' || assignor.acceptance===true) {
            assignor.acceptance=true;
        } else {
            assignor.acceptance=false;
        }

        return assignor;
    }
}]);

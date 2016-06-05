'use strict';

/**
 * Employee controller
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
SchoolApp.controller('EmployeeController', ['$rootScope', '$scope', '$filter', '$stateParams', 'Restful', 'Paginator',
    function($rootScope, $scope, $filter, $stateParams, Restful, Paginator) {
    var path='employee';

    $scope.employee={};
    $scope.employees=[];
    $scope.pagination= {
        perPage: 20,
        currentPage: 1,
        totalPages: 0,
        totalItems: 0,
        currentItem: 0,
        totalCurrentPage: 0, 
        pageNumbers: []
    };
    var pages={
        all: [],
        filtered: [],
        list: [],
        paginated: []
    };

    $scope.getAll=function(){
        Restful.get(path, function(data) {
            var data=$rootScope.orderSimple(data.data, 'name');
            var page=Paginator.setPaginationData(data, data, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.employees=pages.list;
        });
    }

    if ($stateParams.employee_id!=undefined) {
        Restful.get(path+'/'+$stateParams.employee_id, function(data) {
            $scope.employee=data;
        });
    }
    
    $scope.save=function(data)
    {
        var employee=angular.copy(data);
        Restful.save(path, employee, function(data) {
            flash.success('Salvo!');
            dd(data);
        }, function(response) {
            showErrors(response.data);
            dd(response.data);
        });
    }

    $scope.delete=function(employee)
    {
        Restful.remove(path, employee, function(response) {
            var index=$scope.employees.indexOf(employee);
            $scope.employees.splice(index,1);

            flash.success('Funcionário(a): '+employee.name+' excluído(a) com sucesso!');
        }, function(response) {
            flash.error('Não foi possível excluir o registro. É necessário excluir todas as relações antes prosseguir.');
        });
    }

        $scope.doFilter= function(search)
        {
                var filtered = $filter('filter')(pages.all, search);
                var page=Paginator.setPaginationData(pages.all, filtered, $scope.pagination)
                $scope.pagination=page.pagination;
                pages=page.entity;
                $scope.employees=pages.list;
                $scope.setPage(1);
        };

        /**
         * Ordena objetos
         */
        $scope.order = function(property) {
            var employeesOrder = OrderService.get('employees', property);

            pages.all=$filter('orderBy')(pages.all, pagesOrder);
            pages.filtered=$filter('orderBy')(pages.filtered, employeesOrder);
            var page=Paginator.setPaginationData(employees.all, employees.filtered, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.employees=pages.list;
            $scope.setPage(1);
        };

        /**
         * Ordem que está ordenado a propriedade
         */
        $scope.byOrder = function(property) {
            return OrderService.byOrder('employees', property);
        };

        $scope.setPage=function(page)
        {
            var data=Paginator.page(pages, page, $scope.pagination);
            $scope.pagination=data.pagination;
            $scope.employees=data.entity.list;
        }
        $scope.previous=function()
        {
            $scope.setPage($scope.pagination.currentPage-1);
        }
        $scope.next=function()
        {
            $scope.setPage($scope.pagination.currentPage+1);
        }
}]);
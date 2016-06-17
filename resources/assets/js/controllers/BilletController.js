'use strict';

/**
 * Billet controller
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
SchoolApp.controller('BilletController', ['$rootScope', '$scope', '$filter', '$stateParams', 'Restful', 'BilletService', 'Paginator',
    function($rootScope, $scope, $filter, $stateParams, Restful, BilletService, Paginator) {
    var path='billet';

    $scope.billets=[];
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
            var data=BilletService.prepareBillets($rootScope.orderSimple(data.data, 'student.name'));
            var page=Paginator.setPaginationData(data, data, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.billets=pages.list;
        });
    }

    $scope.getDefaulters=function() {
        Restful.get(path+'/defaulters', function(data) {
            $scope.billets=BilletService.prepareBillets($rootScope.orderSimple(data, 'student.name'));
        });
    }

    if ($stateParams.billet_id!=undefined) {
        Restful.get(path+'/'+$stateParams.billet_id, function(data) {
            $scope.billet=data;
        });
    }
    
    $scope.renderPDF=function(billet)
    {
        billet.printing=true;
        Restful.get(path+'/'+billet.id+'/pdf', function(data) {
            document.getElementById('inputPDF').value=data;
            document.getElementById('formPDF').submit();
            billet.printing=false;
        });
    }
    $scope.save=function(data)
    {
        var billet=angular.copy(data);

        Restful.save(path, billet, function(data) {
            flash.success('Salvo!');
            dd(data);
        }, function(response) {
            showErrors(response.data);
            dd(response.data);
        });
    }

    $scope.delete=function(billet)
    {
        Restful.remove(path, billet, function(response) {
            var index=$scope.billets.indexOf(billet);
            $scope.billets.splice(index,1);

            flash.success('Boleto: '+billet.id+' excluído com sucesso!');
        }, function(response) {
            flash.error('Não foi possível excluir o registro. É necessário excluir todas as relações antes prosseguir.');
        });
    }

    $scope.pay=function(billet)
    {
        BilletService.pay(billet, function(data){
            var index=$scope.billets.indexOf(billet);
            $scope.billets[index]=BilletService.prepareBillet(data);
            
            return flash.success('Conta quitada!');
        });
    }


        $scope.doFilter= function(search)
        {
                var filtered = $filter('filter')(pages.all, search);
                var page=Paginator.setPaginationData(pages.all, filtered, $scope.pagination)
                $scope.pagination=page.pagination;
                pages=page.entity;
                $scope.billets=pages.list;
                $scope.setPage(1);
        };

        /**
         * Ordena objetos
         */
        $scope.order = function(property) {
            var billetsOrder = OrderService.get('billets', property);

            pages.all=$filter('orderBy')(pages.all, pagesOrder);
            pages.filtered=$filter('orderBy')(pages.filtered, billetsOrder);
            var page=Paginator.setPaginationData(billets.all, billets.filtered, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.billets=pages.list;
            $scope.setPage(1);
        };

        /**
         * Ordem que está ordenado a propriedade
         */
        $scope.byOrder = function(property) {
            return OrderService.byOrder('billets', property);
        };

        $scope.setPage=function(page)
        {
            var data=Paginator.page(pages, page, $scope.pagination);
            $scope.pagination=data.pagination;
            $scope.billets=data.entity.list;
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

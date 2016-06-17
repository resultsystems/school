'use strict';

/**
 * Matter controller
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
SchoolApp.controller('MatterController', ['$rootScope', '$scope', '$filter', '$stateParams', 'Restful', 'Paginator', 
    function($rootScope, $scope, $filter, $stateParams, Restful, Paginator) {

    var path='matter';

    $scope.matter={};
    $scope.matters=[];
    $scope.lessons=[];
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
            $scope.matters=pages.list;
        });
    }

    $scope.getLessons=function()
    {
        $scope.getAll();
        setTimeout(function() {
            $("#matterMatter").select2().trigger('change');
            Restful.get('lesson', function(data) {
                $scope.lessons=$rootScope.orderSimple(data.data, 'name');
            });
        }, 500);
    }

    $scope.setChecked=function(matter)
    {
        dd(matter);
        for (var l = $scope.lessons.length - 1; l >= 0; l--) {
            $scope.lessons[l].checked=false;
            for (var m = matter.lessons.length - 1; m >= 0; m--) {
                if (matter.lessons[m].id==$scope.lessons[l].id) {
                    $scope.lessons[l].checked=true;
                }
            }
        }
    }

    if ($stateParams.matter_id!=undefined) {
        Restful.get(path+'/'+$stateParams.matter_id, function(data) {
            $scope.matter=data;
        });
    }
    
    $scope.save=function(data)
    {
        var matter=angular.copy(data);
        dd(matter);
        Restful.save(path, matter, function(data) {
            flash.success('Salvo!');
            dd(data);
        }, function(response) {
            showErrors(response.data);
            dd(response.data);
        });
    }

    $scope.delete=function(matter)
    {
        Restful.remove(path, matter, function(response) {
            var index=$scope.matters.indexOf(matter);
            $scope.matters.splice(index,1);

            flash.success('Matéria: '+matter.name+' excluída com sucesso!');
        }, function(response) {
            flash.error('Não foi possível excluir o registro. É necessário excluir todas as relações antes prosseguir.');
        });
    }

    $scope.associateLessons=function(data)
    {
        if (data==undefined || data.id==undefined) {
            return flash.warning('Selecione uma matéria');
        }

        var matter = {
            'lessons' : []
        };
        for (var i = $scope.lessons.length - 1; i >= 0; i--) {
            if ($scope.lessons[i].checked) {
                matter.lessons.push({'id': $scope.lessons[i].id});
            }
        }
        Restful.put(path+'/'+data.id+'/lessons', matter, function(data) {
            flash.success('Salvo!');
            $scope.matter={};
            $scope.getAll();
            $("#matterMatter").select2().trigger('change');
            for (var i = $scope.lessons.length - 1; i >= 0; i--) {
                $scope.lessons[i].checked=false;
            }
        }, function(response) {
            showErrors(response.data);
        });
    }


        $scope.doFilter= function(search)
        {
                var filtered = $filter('filter')(pages.all, search);
                var page=Paginator.setPaginationData(pages.all, filtered, $scope.pagination)
                $scope.pagination=page.pagination;
                pages=page.entity;
                $scope.matters=pages.list;
                $scope.setPage(1);
        };

        /**
         * Ordena objetos
         */
        $scope.order = function(property) {
            var mattersOrder = OrderService.get('matters', property);

            pages.all=$filter('orderBy')(pages.all, pagesOrder);
            pages.filtered=$filter('orderBy')(pages.filtered, mattersOrder);
            var page=Paginator.setPaginationData(matters.all, matters.filtered, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.matters=pages.list;
            $scope.setPage(1);
        };

        /**
         * Ordem que está ordenado a propriedade
         */
        $scope.byOrder = function(property) {
            return OrderService.byOrder('matters', property);
        };

        $scope.setPage=function(page)
        {
            var data=Paginator.page(pages, page, $scope.pagination);
            $scope.pagination=data.pagination;
            $scope.matters=data.entity.list;
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

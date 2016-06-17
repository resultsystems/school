'use strict';

/**
 * Lesson controller
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
SchoolApp.controller('LessonController', ['$rootScope', '$scope', '$filter', '$stateParams', 'Restful', 'Paginator',
    function($rootScope, $scope, $filter, $stateParams, Restful, Paginator) {

    var path='lesson';

    $scope.lesson={};
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
            $scope.lessons=pages.list;
        });
    }

    if ($stateParams.lesson_id!=undefined) {
        Restful.get(path+'/'+$stateParams.lesson_id, function(data) {
            $scope.lesson=data;
        });
    }
    
    $scope.save=function(data)
    {
        var lesson=angular.copy(data);
        dd(lesson);
        Restful.save(path, lesson, function(data) {
            flash.success('Salvo!');
            dd(data);
        }, function(response) {
            showErrors(response.data);
            dd(response.data);
        });
    }

    $scope.delete=function(lesson)
    {
        Restful.remove(path, lesson, function(response) {
            var index=$scope.lessons.indexOf(lesson);
            $scope.lessons.splice(index,1);

            flash.success('Lição: '+lesson.name+' excluída com sucesso!');
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
                $scope.lessons=pages.list;
                $scope.setPage(1);
        };

        /**
         * Ordena objetos
         */
        $scope.order = function(property) {
            var lessonsOrder = OrderService.get('lessons', property);

            pages.all=$filter('orderBy')(pages.all, pagesOrder);
            pages.filtered=$filter('orderBy')(pages.filtered, lessonsOrder);
            var page=Paginator.setPaginationData(lessons.all, lessons.filtered, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.lessons=pages.list;
            $scope.setPage(1);
        };

        /**
         * Ordem que está ordenado a propriedade
         */
        $scope.byOrder = function(property) {
            return OrderService.byOrder('lessons', property);
        };

        $scope.setPage=function(page)
        {
            var data=Paginator.page(pages, page, $scope.pagination);
            $scope.pagination=data.pagination;
            $scope.lessons=data.entity.list;
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

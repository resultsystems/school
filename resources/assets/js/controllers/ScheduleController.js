'use strict';

/**
 * Schedule controller
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
SchoolApp.controller('ScheduleController', ['$rootScope', '$scope', '$filter', '$stateParams', 'Restful', 'Paginator', 
    function($rootScope, $scope, $filter, stateParams, Restful, Paginator) {

    var path='schedule';

    $scope.schedules=[];
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
            var data=prepareSchedules($rootScope.orderSimple(data.data, 'name'));
            var page=Paginator.setPaginationData(data, data, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.schedules=pages.list;
        });
    }

    if (stateParams.schedule_id!=undefined) {
        Restful.get(path+'/'+stateParams.schedule_id, function(data) {
            $scope.schedule=data;
        });
    }

    $scope.save=function(data)
    {
        var schedule=angular.copy(data);
        dd(schedule);
        Restful.save(path, schedule, function(data) {
            flash.success('Salvo!');
            dd(data);
        }, function(response) {
            showErrors(response.data);
            dd(response.data);
        });
    }

    $scope.delete=function(schedule)
    {
        Restful.remove(path, schedule, function(data) {
            dd(data);
            var index=$scope.schedules.indexOf(schedule);
            $scope.schedules.splice(index,1);

            flash.success('Horário: '+schedule.name+' excluído com sucesso!');
        }, function(response) {
            flash.error('Não foi possível excluir o registro. É necessário excluir todas as relações antes de prosseguir.');
        });
    }

    var prepareSchedules=function(schedules)
    {
        for (var i = schedules.length - 1; i >= 0; i--) {
            schedules[i].start=moment('2016-01-01 ' +schedules[i].start).format('HH:mm');
            schedules[i].end=moment('2016-01-01 ' +schedules[i].end).format('HH:mm');
        }
        
        return schedules;
    }


        $scope.doFilter= function(search)
        {
                var filtered = $filter('filter')(pages.all, search);
                var page=Paginator.setPaginationData(pages.all, filtered, $scope.pagination)
                $scope.pagination=page.pagination;
                pages=page.entity;
                $scope.schedules=pages.list;
                $scope.setPage(1);
        };

        /**
         * Ordena objetos
         */
        $scope.order = function(property) {
            var schedulesOrder = OrderService.get('schedules', property);

            pages.all=$filter('orderBy')(pages.all, pagesOrder);
            pages.filtered=$filter('orderBy')(pages.filtered, schedulesOrder);
            var page=Paginator.setPaginationData(schedules.all, schedules.filtered, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.schedules=pages.list;
            $scope.setPage(1);
        };

        /**
         * Ordem que está ordenado a propriedade
         */
        $scope.byOrder = function(property) {
            return OrderService.byOrder('schedules', property);
        };

        $scope.setPage=function(page)
        {
            var data=Paginator.page(pages, page, $scope.pagination);
            $scope.pagination=data.pagination;
            $scope.schedules=data.entity.list;
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


'use strict';

/**
 * Teacher controller
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
SchoolApp.controller('TeacherController', ['$rootScope', '$scope', '$filter', '$stateParams', 'Restful', 'StatesAndCities', 'Postcode', 'Paginator', 
    function($rootScope, $scope, $filter, $stateParams, Restful, StatesAndCities, Postcode, Paginator) {

    $scope.teachers=[];
    $scope.matters=[];
    $scope.teacher={'sex': 'male', 'type_salary':'class_time'};
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

    var path='teacher';


    $scope.gets=function()
    {
        $scope.states=StatesAndCities.getStates();
        setTimeout(function() {
            $('#teacherState').select2().trigger('change');
        }, 100);
    }
    $scope.cities=[];

    $scope.getAll=function(){
        Restful.get(path, function(data) {
            var data=prepareTeachers($rootScope.orderSimple(data.data, 'name'));
            var page=Paginator.setPaginationData(data, data, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.teachers=pages.list;
        });
    }

    if ($stateParams.teacher_id!=undefined) {
        Restful.get(path+'/'+$stateParams.teacher_id, function(data) {
            var teacher=angular.copy(data);
            $scope.teacher=data;
            $scope.setState($scope.teacher.state);

            setTimeout(function() {
               $("#teacherState").select2().trigger('change');
                setTimeout(function() {
                    $scope.teacher.city=teacher.city;
                    $("#teacherCity").select2().trigger('change');
                }, 100);
            }, 100);

        });
    }

    $scope.setState=function(state)
    {
        $scope.teacher.city=undefined;
        StatesAndCities.setState(state);
        $scope.cities=StatesAndCities.getCities(state);
        setTimeout(function() {
            $("#teacherCity").select2().trigger('change');
        }, 200);
    }

    var setAddress=function(address)
    {
            $scope.teacher.street=address.logradouro;
            $scope.teacher.district=address.bairro;
            $scope.teacher.state=address.uf;

           $scope.setState($scope.teacher.state);

            setTimeout(function() {
               $("#teacherState").select2().trigger('change');
                setTimeout(function() {
                    $scope.teacher.city=address.localidade;
                    $("#teacherCity").select2().trigger('change');
                    $("#teacherNumber").focus();
                }, 100);
            }, 100);
    }

    $scope.getPostcode=function(postcode)
    {
        Postcode.get(postcode, function(address){
            setAddress(address);
        });
    }

    $scope.save=function(data)
    {
        var teacher=angular.copy(data);
        teacher.cpf=clearDigits(teacher.cpf);
        teacher.postcode=clearDigits(teacher.postcode);
        teacher.phone=clearDigits(teacher.phone);
        teacher.cellphone=clearDigits(teacher.cellphone);
        Restful.save(path, teacher,function(data) {
            flash.success('Salvo!');
            dd(data);
        }, function(response) {
            showErrors(response.data);
            dd(response.data);
        });
    }


    $scope.delete=function(teacher)
    {
        Restful.remove(path, teacher, function(response) {
            var index=$scope.teachers.indexOf(teacher);
            $scope.teachers.splice(index,1);

            flash.success('Professor(a): '+teacher.name+' excluído(a) com sucesso!');
        }, function(response) {
            flash.error('Não foi possível excluir o registro. É necessário excluir todas as relações antes prosseguir.');
        });
    }

    $scope.associateMatters=function(data)
    {
        if (data==undefined || data.id==undefined) {
            return flash.warning('Selecione uma turma');
        }
        var teacher = {
            'matters': []
        };
        for (var i = $scope.matters.length - 1; i >= 0; i--) {
            if ($scope.matters[i].checked) {
                teacher.matters.push({'id': $scope.matters[i].id});
            }
        }
        Restful.put(path+'/'+data.id+'/matters', teacher, function(data) {
            flash.success('Salvo!');
            $scope.teacher={};
            $scope.getAll();
            $("#teacherTeacher").select2().trigger('change');
            for (var i = $scope.matters.length - 1; i >= 0; i--) {
                $scope.matters[i].checked=false;
            }
        }, function(response) {
            showErrors(response.data);
        });
    }

    $scope.setChecked=function(teacher)
    {
        for (var m = $scope.matters.length - 1; m >= 0; m--) {
            $scope.matters[m].checked=false;
            for (var i = teacher.matters.length - 1; i >= 0; i--) {
                if (teacher.matters[i].id==$scope.matters[m].id) {
                    $scope.matters[m].checked=true;
                }
            }
        }
    }

    $scope.getMatters=function()
    {
        $scope.getAll();

        setTimeout(function() {
            $("#teacherTeacher").select2().trigger('change');
            Restful.get('matter', function(data) {
                $scope.matters=$rootScope.orderSimple(data.data, 'name');
            });
        }, 500);
    }

    var prepareTeachers=function(teachers)
    {
        for (var i = teachers.length - 1; i >= 0; i--) {
            if (teachers[i].cpf!=undefined) {
                teachers[i].cpf=formats(teachers[i].cpf).cpf();
            }
            if (teachers[i].phone!=undefined) {
                teachers[i].phone=formats(teachers[i].phone).phone();
            }
            if (teachers[i].cellphone!=undefined) {
                teachers[i].cellphone=formats(teachers[i].cellphone).phone();
            }
        }

        return teachers;
    }


        $scope.doFilter= function(search)
        {
                var filtered = $filter('filter')(pages.all, search);
                var page=Paginator.setPaginationData(pages.all, filtered, $scope.pagination)
                $scope.pagination=page.pagination;
                pages=page.entity;
                $scope.teachers=pages.list;
                $scope.setPage(1);
        };

        /**
         * Ordena objetos
         */
        $scope.order = function(property) {
            var teachersOrder = OrderService.get('teachers', property);

            pages.all=$filter('orderBy')(pages.all, pagesOrder);
            pages.filtered=$filter('orderBy')(pages.filtered, teachersOrder);
            var page=Paginator.setPaginationData(teachers.all, teachers.filtered, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.teachers=pages.list;
            $scope.setPage(1);
        };

        /**
         * Ordem que está ordenado a propriedade
         */
        $scope.byOrder = function(property) {
            return OrderService.byOrder('teachers', property);
        };

        $scope.setPage=function(page)
        {
            var data=Paginator.page(pages, page, $scope.pagination);
            $scope.pagination=data.pagination;
            $scope.teachers=data.entity.list;
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



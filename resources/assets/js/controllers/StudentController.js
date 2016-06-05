'use strict';

/**
 * Student controller
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
SchoolApp.controller('StudentController', ['$rootScope', '$scope', '$filter', '$stateParams', 'Restful', 'StatesAndCities', 'Postcode', 'BilletService', 'Paginator', 
    function($rootScope, $scope, $filter, $stateParams, Restful, StatesAndCities, Postcode, BilletService, Paginator) {

    var path='student';

    $scope.students=[];
    $scope.student={'sex': 'male'};
    $scope.classrooms=[];
    $scope.cities=[];
    var matter_completeds=[];
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

    $scope.setState=function(state)
    {
        $scope.student.city=undefined;
        StatesAndCities.setState(state);
        $scope.cities=StatesAndCities.getCities(state);
        setTimeout(function() {
            $("#studentCity").select2().trigger('change');
        }, 200);
    }

    $scope.getBillets=function()
    {
        Restful.get(path+'/'+$stateParams.id+'/billets/', function(data) {
            data.billets=BilletService.prepareBillets(data.billets);
            $scope.student=prepareStudent(data);
        });
    }

    $scope.getClassroomWithMatters=function()
    {
        Restful.get(path+'/'+$stateParams.id, function(data) {
            $scope.student=data;
        });            
        Restful.get(path+'/'+$stateParams.id+'/classrooms/matters/', function(data) {
            matter_completeds=data.matter_completeds;
            $scope.classrooms=prepareCheckeds(data.classrooms);
        });
    }

    $scope.gets=function()
    {
        $scope.states=StatesAndCities.getStates();
        setTimeout(function() {
            $('#studentState').select2().trigger('change');
        }, 100);
    }

   
    $scope.getAll=function(){
        Restful.get(path, function(data) {
            var data=prepareStudents($rootScope.orderSimple(data.data, 'name'));
            var page=Paginator.setPaginationData(data, data, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.students=pages.list;
        });
    }

    getStudent=function(id)
    {
        Restful.get(path+'/'+id, function(data) {
            var student=angular.copy(data);
            $scope.student=data;
            $scope.setState($scope.student.state);

            setTimeout(function() {
               $("#studentState").select2().trigger('change');
                setTimeout(function() {
                    $scope.student.city=student.city;
                    $("#studentCity").select2().trigger('change');
                }, 100);
            }, 100);
        });
    }

    if ($stateParams.student_id!=undefined) {
        getStudent($stateParams.student_id);
    }

    if ($stateParams.me!=undefined) {
        $scope.student=$rootScope.user.owner;
    }

    $scope.delete=function(student)
    {
        Restful.remove(path, student, function(response) {
            var index=$scope.students.indexOf(student);
            $scope.students.splice(index,1);

            flash.success('Aluno(a): '+student.name+' excluído(a) com sucesso!');
        }, function(response) {
            flash.error('Não foi possível excluir o registro. É necessário excluir todas as relações antes prosseguir.');
        });
    }

    var setAddress=function(address)
    {
            $scope.student.street=address.logradouro;
            $scope.student.district=address.bairro;
            $scope.student.state=address.uf;

           $scope.setState($scope.student.state);

            setTimeout(function() {
               $("#studentState").select2().trigger('change');
                setTimeout(function() {
                    $scope.student.city=address.localidade;
                    $("#studentCity").select2().trigger('change');
                    $("#studentNumber").focus();
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
        var student=angular.copy(data);
        student.cpf=clearDigits(student.cpf);
        student.postcode=clearDigits(student.postcode);
        student.phone=clearDigits(student.phone);
        student.cellphone=clearDigits(student.cellphone);
        student.cpf_responsible=clearDigits(student.cpf_responsible);
        student.phone_father=clearDigits(student.phone_father);
        student.phone_mother=clearDigits(student.phone_mother);
        student.phone_responsible=clearDigits(student.phone_responsible);
        Restful.save(path, student, function(data) {
            flash.success('Aluno(a): '+data.name+' salvo(a) com sucesso!');
            dd(data);
        }, function(response) {
            showErrors(response.data);
            dd(response.data);
        });
    }

    var prepareStudent=function(student)
    {
            student.phone=formats(student.phone).phone();
            student.cellphone=formats(student.cellphone).phone();
            student.has_overdue=yesNo(student.has_delayed);

        return student;
    }

    var prepareStudents=function(students)
    {
        for (var i = students.length - 1; i >= 0; i--) {
            students[i]=prepareStudent(students[i]);
        }

        return students;
    }

    $scope.pay=function(billet)
    {
        BilletService.pay(billet, function(data){
            var index=$scope.student.billets.indexOf(billet);
            $scope.student.billets[index]=BilletService.prepareBillet(data);

            return flash.success('Conta quitada!');
        });
    }

    var prepareCheckeds=function(classrooms)
    {
        for (var c = classrooms.length - 1; c>= 0; c--) {
            classrooms[c]=verifyCheckeds(classrooms[c]);
        }

        return classrooms;
    }

    var verifyCheckeds=function(classroom)
    {
        for (var m = classroom.matters.length - 1; m >= 0; m--) {
            classroom.matters[m].checked=false;
            classroom.matters[m]=verifyChecked(classroom.matters[m], classroom.id);
        }

        return classroom;
    }

    var verifyChecked=function(matter, classroom_id)
    {
        for (var i = matter_completeds.length - 1; i >= 0; i--) {
            if (classroom_id==22) {
                dd(classroom_id, matter_completeds[i].classroom_id,
                    matter_completeds[i].matter_id, matter.id, matter_completeds[i]);
            }
            if (classroom_id==matter_completeds[i].classroom_id
                && matter_completeds[i].matter_id==matter.id) {
                matter.checked=true;
            }
        }

        return matter;
    }

    $scope.syncMattersCompleteds=function(classrooms)
    {
        var data=[];
        for (var c = classrooms.length - 1; c >= 0; c--) {
            for (var m = classrooms[c].matters.length - 1; m >= 0; m--) {
                if (classrooms[c].matters[m].checked) {
                    data.push({
                        'id': classrooms[c].matters[m].pivot.id,
                    });
                }
            }
        }
        Restful.put(path+'/'+$scope.student.id+'/matters/completeds/sync', data, function(data) {
            flash.success('Matérias concluídas!');
        }, function(response) {
            showErrors(response.data);
            dd(response.data);
        });
    }


        $scope.doFilter= function(search)
        {
                var filtered = $filter('filter')(pages.all, search);
                var page=Paginator.setPaginationData(pages.all, filtered, $scope.pagination)
                $scope.pagination=page.pagination;
                pages=page.entity;
                $scope.students=pages.list;
                $scope.setPage(1);
        };

        /**
         * Ordena objetos
         */
        $scope.order = function(property) {
            var studentsOrder = OrderService.get('students', property);

            pages.all=$filter('orderBy')(pages.all, pagesOrder);
            pages.filtered=$filter('orderBy')(pages.filtered, studentsOrder);
            var page=Paginator.setPaginationData(students.all, students.filtered, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.students=pages.list;
            $scope.setPage(1);
        };

        /**
         * Ordem que está ordenado a propriedade
         */
        $scope.byOrder = function(property) {
            return OrderService.byOrder('students', property);
        };

        $scope.setPage=function(page)
        {
            var data=Paginator.page(pages, page, $scope.pagination);
            $scope.pagination=data.pagination;
            $scope.students=data.entity.list;
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

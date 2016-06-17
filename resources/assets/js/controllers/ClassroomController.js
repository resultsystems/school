'use strict';
/**
 * Classroom controller
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
SchoolApp.controller('ClassroomController', ['$rootScope', '$scope', '$filter', '$stateParams', 'Restful', 'Paginator',
    function($rootScope, $scope, $filter, $stateParams, Restful, Paginator) {
    var path='classroom';

    $scope.classroom={};
    $scope.classrooms=[];
    $scope.matters=[];
    $scope.schedules=[];
    $scope.students=[];
    $scope.teachers=[];
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
    
    var getClassrooms=function(){
        Restful.get(path, function(data) {
            var data=prepareClassrooms($rootScope.orderSimple(data.data, 'name'));
            var page=Paginator.setPaginationData(data, data, $scope.pagination)
            $scope.pagination=page.pagination;
            pages=page.entity;
            $scope.classrooms=pages.list;
        });
    }

    $scope.getAll=function(){
        getClassrooms();
    }

    $scope.getMatters=function()
    {
        getClassrooms();

        setTimeout(function() {
            $("#classroomClassroom").select2().trigger('change');
            Restful.get('matter', function(data) {
                $scope.matters=$rootScope.orderSimple(data.data, 'name');
            });
        }, 500);
    }

    $scope.getStudents=function()
    {
        getClassrooms();

        setTimeout(function() {
            $("#classroomClassroom").select2().trigger('change');
            Restful.get('student', function(data) {
                $scope.students=$rootScope.orderSimple(data.data, 'name');
            });
        }, 500);
    }

    $scope.gets=function()
    {
        Restful.get('teacher', function(data) {
            $scope.teachers=$rootScope.orderSimple(data.data, 'name');
            setTimeout(function() {
                $("#classroomTeacher").select2().trigger('change');
            }, 100)
        });
        Restful.get('schedule', function(data) {
            $scope.schedules=$rootScope.orderSimple(data.data, 'name');
            setTimeout(function() {
                $("#classroomSchedule").select2().trigger('change');
            }, 100)
        });
    }

    $scope.setChecked=function(classroom)
    {
        for (var m = $scope.matters.length - 1; m >= 0; m--) {
            $scope.matters[m].checked=false;
            for (var i = classroom.matters.length - 1; i >= 0; i--) {
                if (classroom.matters[i].id==$scope.matters[m].id) {
                    $scope.matters[m].checked=true;
                }
            }
        }
    }

    $scope.setCheckedStudents=function(classroom)
    {
        for (var s = $scope.students.length - 1; s >= 0; s--) {
            $scope.students[s].checked=false;
            for (var i = classroom.students.length - 1; i >= 0; i--) {
                if (classroom.students[i].id==$scope.students[s].id) {
                    $scope.students[s].checked=true;
                }
            }
        }
    }

    if ($stateParams.id!=undefined) {
        Restful.get(path+'/'+$stateParams.id+'/students', function(data) {
            dd(data);
            $scope.classroom=data.classroom;
            $scope.students=prepareStudents($rootScope.orderSimple(data.students, 'name'));
        });
    }

    if ($stateParams.classroom_id!=undefined) {
        Restful.get(path+'/'+$stateParams.classroom_id, function(data) {
            dd(data);
            $scope.classroom=data;
            setTimeout(function() {
                   $("#classroomTeacher").select2().trigger('change');
                   $("#classroomSchedule").select2().trigger('change');
            }, 100);

        });
    }

    $scope.save=function(data)
    {
        var classroom=angular.copy(data);
        Restful.save(path, classroom, function(data) {
            flash.success('Salvo!');
            dd(data);
        }, function(response) {
            showErrors(response.data);
            dd(response.data);
        });
    }

    $scope.delete=function(classroom)
    {
        Restful.remove(path, classroom, function(response) {
            var index=$scope.classrooms.indexOf(classroom);
            $scope.classrooms.splice(index,1);

            flash.success('Turma: '+classroom.name+' excluíd com sucesso!');
        }, function(response) {
            flash.error('Não foi possível excluir o registro. É necessário excluir todas as relações antes prosseguir.');
        });
    }

    $scope.associateMatters=function(data)
    {
        if (data==undefined || data.id==undefined) {
            return flash.warning('Selecione uma turma');
        }
        var classroom = {
            'matters': []
        };
        for (var i = $scope.matters.length - 1; i >= 0; i--) {
            if ($scope.matters[i].checked) {
                classroom.matters.push({'id': $scope.matters[i].id});
            }
        }
        Restful.put(path+'/'+data.id+'/matters', classroom, function(data) {
            flash.success('Salvo!');
            $scope.classroom={};
            getClassrooms();
            $("#classroomClassroom").select2().trigger('change');
            for (var i = $scope.matters.length - 1; i >= 0; i--) {
                $scope.matters[i].checked=false;
            }
        }, function(response) {
            showErrors(response.data);
        });
    }

    $scope.associateStudents=function(data)
    {
        if (data==undefined || data.id==undefined) {
            return flash.warning('Selecione uma turma');
        }
        var classroom = {
            'students': []
        };
        for (var i = $scope.students.length - 1; i >= 0; i--) {
            if ($scope.students[i].checked) {
                classroom.students.push({'id': $scope.students[i].id});
            }
        }
        Restful.put(path+'/'+data.id+'/students', classroom, function(data) {
            flash.success('Salvo!');
            $scope.classroom={};
            getClassrooms();
            $("#classroomClassroom").select2().trigger('change');
            for (var i = $scope.students.length - 1; i >= 0; i--) {
                $scope.students[i].checked=false;
            }
        }, function(response) {
            showErrors(response.data);
        });
    }

    var prepareClassroom=function(classroom)
    {
        classroom.schedule.start=moment('2016-01-01 ' +classroom.schedule.start).format('HH:mm');
        classroom.schedule.end=moment('2016-01-01 ' +classroom.schedule.end).format('HH:mm');

        return classroom;
    }

    var prepareClassrooms=function(classrooms)
    {
        for (var i = classrooms.length - 1; i >= 0; i--) {
            classrooms[i]=prepareClassroom(classrooms[i]);
        }

        return classrooms;
    }

    var prepareStudent=function(student)
    {
        student.phone=formats(student.phone).phone();
        student.cellphone=formats(student.cellphone).phone();

        return student;
    }

    var prepareStudents=function(students)
    {
        for (var i = students.length - 1; i >= 0; i--) {
            students[i]=prepareStudent(students[i]);
        }

        return students;
    }

    $scope.mattersCompleteds=function(classrooms)
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
        Restful.put(path+'/matters/completeds', data, function(data) {
            flash.success('Matérias concluídas para todos os alunos!');
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
            $scope.classrooms=pages.list;
            $scope.setPage(1);
    };

    /**
     * Ordena objetos
     */
    $scope.order = function(property) {
        var classroomsOrder = OrderService.get('classrooms', property);

        pages.all=$filter('orderBy')(pages.all, pagesOrder);
        pages.filtered=$filter('orderBy')(pages.filtered, classroomsOrder);
        var page=Paginator.setPaginationData(classrooms.all, classrooms.filtered, $scope.pagination)
        $scope.pagination=page.pagination;
        pages=page.entity;
        $scope.classrooms=pages.list;
        $scope.setPage(1);
    };

    /**
     * Ordem que está ordenado a propriedade
     */
    $scope.byOrder = function(property) {
        return OrderService.byOrder('classrooms', property);
    };

    $scope.setPage=function(page)
    {
        var data=Paginator.page(pages, page, $scope.pagination);
        $scope.pagination=data.pagination;
        $scope.classrooms=data.entity.list;
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

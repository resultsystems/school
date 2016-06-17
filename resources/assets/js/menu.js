SchoolApp.config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
    $stateProvider
        .state('licoes', {
            url: "/licoes",
            templateUrl: "views/licoes/index.html",
            data: {pageTitle: 'Lista de lições'},
            controller: "LessonController"
        })

        .state('licoesCadastro', {
            url: "/licoes/cadastro",
            templateUrl: "views/licoes/cadastro.html",
            data: {pageTitle: 'Cadastro de lições'},
            controller: "LessonController"
        })

        .state('licoesEditar', {
            url: "/licoes/{lesson_id:[0-9]{1,8}}",
            templateUrl: "views/licoes/cadastro.html",
            data: {pageTitle: 'Editar cadastro de lição'},
            controller: "LessonController"
        })

        .state('turmas', {
            url: "/turmas",
            templateUrl: "views/turmas/index.html",
            data: {pageTitle: 'Lista de turmas'},
            controller: "ClassroomController"
        })

        .state('turmasCadastro', {
            url: "/turmas/cadastro",
            templateUrl: "views/turmas/cadastro.html",
            data: {pageTitle: 'Cadastro de turmas'},
            controller: "ClassroomController"
        })

        .state('turmasCadastroMaterias', {
            url: "/turmas/cadastro/materias",
            templateUrl: "views/turmas/materias.html",
            data: {pageTitle: 'Associar matérias a turmas'},
            controller: "ClassroomController"
        })

        .state('turmasCadastroAlunos', {
            url: "/turmas/cadastro/alunos",
            templateUrl: "views/turmas/alunos.html",
            data: {pageTitle: 'Associar alunos a turmas'},
            controller: "ClassroomController"
        })

        .state('alunosInadimplentes', {
            url: "/alunos/inadimplentes",
            templateUrl: "views/pagamentos/inadimplentes.html",
            data: {pageTitle: 'inadimplentes'},
            controller: "BilletController"
        })

        .state('alunoBoletos', {
            url: "/alunos/{id:[0-9]{1,8}}/boletos",
            templateUrl: "views/pagamentos/aluno_boletos.html",
            data: {pageTitle: 'Lista de boletos do aluno'},
            controller: "StudentController"
        })

        .state('alunoMaterias', {
            url: "/alunos/{id:[0-9]{1,8}}/materias",
            templateUrl: "views/alunos/aluno_materias.html",
            data: {pageTitle: 'Lista de boletos do aluno'},
            controller: "StudentController"
        })

        .state('turmasAlunos', {
            url: "/turmas/{id:[0-9]{1,8}}/alunos",
            templateUrl: "views/turmas/lista_alunos.html",
            data: {pageTitle: 'Lista de Alunos'},
            controller: "ClassroomController"
        })

        .state('turmasEditar', {
            url: "/turmas/{classroom_id:[0-9]{1,8}}",
            templateUrl: "views/turmas/cadastro.html",
            data: {pageTitle: 'Editar cadastro de turmas'},
            controller: "ClassroomController"
        })

        .state('materias', {
            url: "/materias",
            templateUrl: "views/materias/index.html",
            data: {pageTitle: 'Lista de matérias'},
            controller: "MatterController"
        })

        .state('materiasCadastro', {
            url: "/materias/cadastro",
            templateUrl: "views/materias/cadastro.html",
            data: {pageTitle: 'Cadastro de matérias'},
            controller: "MatterController"
        })

        .state('materiasCadastroLicoes', {
            url: "/materias/cadastro/licoes",
            templateUrl: "views/materias/licoes.html",
            data: {pageTitle: 'Associar lições a matérias'},
            controller: "MatterController"
        })

        .state('materiasEditar', {
            url: "/materias/{matter_id:[0-9]{1,8}}",
            templateUrl: "views/materias/cadastro.html",
            data: {pageTitle: 'Editar cadastro de matéria'},
            controller: "MatterController"
        })

        .state('funcionarios', {
            url: "/funcionarios",
            templateUrl: "views/funcionarios/index.html",
            data: {pageTitle: 'Lista de funcionários'},
            controller: "EmployeeController"
        })

        .state('funcionariosCadastro', {
            url: "/funcionarios/cadastro",
            templateUrl: "views/funcionarios/cadastro.html",
            data: {pageTitle: 'Cadastro de funcionário'},
            controller: "EmployeeController"
        })

        .state('funcionariosEditar', {
            url: "/funcionarios/{employee_id:[0-9]{1,8}}",
            templateUrl: "views/funcionarios/cadastro.html",
            data: {pageTitle: 'Editar cadastro de funcionário'},
            controller: "EmployeeController"
        })

        .state('professores', {
            url: "/professores",
            templateUrl: "views/professores/index.html",
            data: {pageTitle: 'Lista de professores'},
            controller: "TeacherController"
        })

        .state('professoresCadastro', {
            url: "/professores/cadastro",
            templateUrl: "views/professores/cadastro.html",
            data: {pageTitle: 'Cadastro de professores'},
            controller: "TeacherController"
        })

        .state('professoresCadastroMaterias', {
            url: "/professores/cadastro/materias",
            templateUrl: "views/professores/materias.html",
            data: {pageTitle: 'Associar matérias a professores'},
            controller: "TeacherController"
        })

        .state('professoresEditar', {
            url: "/professores/{teacher_id:[0-9]{1,8}}",
            templateUrl: "views/professores/cadastro.html",
            data: {pageTitle: 'Editar cadastro de professor'},
            controller: "TeacherController"
        })

        .state('alunos', {
            url: "/alunos",
            templateUrl: "views/alunos/index.html",
            data: {pageTitle: 'Lista de alunos'},
            controller: "StudentController"
        })

        .state('alunosCadastro', {
            url: "/alunos/cadastro",
            templateUrl: "views/alunos/cadastro.html",
            data: {pageTitle: 'Cadastro de aluno'},
            controller: "StudentController"
        })

        .state('alunosEditar', {
            url: "/alunos/{student_id:[0-9]{1,8}}",
            templateUrl: "views/alunos/cadastro.html",
            data: {pageTitle: 'Editar cadastro de aluno'},
            controller: "StudentController"
        })

        .state('alunosMe', {
            url: "/alunos/{me:me}",
            templateUrl: "views/alunos/me.html",
            data: {pageTitle: 'Meus dados cadastrais'},
            controller: "StudentController"
        })

        .state('horarios', {
            url: "/horarios",
            templateUrl: "views/horarios/index.html",
            data: {pageTitle: 'Lista de horários'},
            controller: "ScheduleController"
        })

        .state('horariosCadastro', {
            url: "/horarios/cadastro",
            templateUrl: "views/horarios/cadastro.html",
            data: {pageTitle: 'Cadastro de horários'},
            controller: "ScheduleController"
        })

        .state('horariosEditar', {
            url: "/horarios/{schedule_id:[0-9]{1,8}}",
            templateUrl: "views/horarios/cadastro.html",
            data: {pageTitle: 'Editar cadastro de horário'},
            controller: "ScheduleController"
        })

        .state('pagamentosCedente', {
            url: "/pagamentos/cedente",
            templateUrl: "views/pagamentos/cedente.html",
            data: {pageTitle: 'Cadastro de cedente'},
            controller: "BilletAssignorController"
        })

        .state('pagamentosBoletos', {
            url: "/pagamentos/boletos",
            templateUrl: "views/pagamentos/boletos.html",
            data: {pageTitle: 'Lista de boletos'},
            controller: "BilletController"
        })

    $urlRouterProvider.otherwise("/inicio.html"); 
}]);

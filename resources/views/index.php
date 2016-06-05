<!DOCTYPE html>
<html ng-app="SchoolApp">
<head>
<title>Escola</title>
</head>

<body ng-controller="AppController" >
        <modal visible="modalLogin" data-title="Autenticação - Administrativo" data-modal-close>
            <ng-include src="'views/auth/login.html'"></ng-include>
        </modal>

    <nav ng-show="token">
    <div class="page-container">
        <div ui-view>
        </div>
    </div>
    </nav>
</body>
</html>

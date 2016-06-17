/**
 * Paginator Directive
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
/**
 * Deve se criar o metodo filterPagePaginator e setPaginatorPerPage
 * que receberá os seguintes valores:
 * Quantidade de resultados por página e a página atual respectivamente
 *
 * Exemplo:
 *
 * $scope.filterPagePaginator=function(_perPage, _currentPage)
 * {
 *      $scope._perPage = perPage;
 *      var item = $scope._searchItem;
 *      item._perPage = perPage;
 *      item.page = currentPage;
 *      $scope.search(item);
 * };
 *
 * $scope.setPaginatorPerPage = function(perPage)
 * {
 *       $scope._perPage = perPage;
 * };
 *
 * deve existar as variaveis: (_perPage, _currentPage, _lastPage, _total, _from, _to)
 * no $scope que inclui a directive, sendo
 * _perPage quantidade de resultados por página
 * _currentPage a página atual
 * _lastPage a última página
 * _total total de registros
 * _from registro inicial da página atual
 * _to útlimo registro da página atual
 */
angular.module('SchoolApp').directive('paginator', ['APP',
    function(APP) {
        return {
            templateUrl: APP.domainViewPath + 'directives/paginator.html',
            restrict: 'E',
            transclude: true,
            replace: true,
            scope: true,
            link: function postLink(scope, element, attrs) {
                scope.paginator = {
                    _irPara: scope._currentPage,
                    _perPage: scope._perPage
                };

                //Próxima página
                scope.nextPagePaginator = function() {
                    if (APP.debug) {
                        console.log("next page paginator");
                    }
                    scope._currentPage++;
                    scope.getPaginatorFilter(scope.paginator._perPage, scope._currentPage);
                };

                //Página anterior
                scope.previousPagePaginator = function() {
                    if (APP.debug) {
                        console.log("previous page paginator");
                    }
                    scope._currentPage--;
                    scope.getPaginatorFilter(scope.paginator._perPage, scope._currentPage);
                };

                //Ir para a página
                scope.gotoPagePaginator = function() {
                    var goTo = scope.paginator._irPara;
                    if (APP.debug) {
                        console.log("###############");
                        console.log(goTo);
                    }
                    /**
                     * Página atual
                     */
                    if (goTo == scope._currentPage) {
                        return;
                    }

                    /**
                     * Página acima da última página
                     */
                    if (goTo > scope._lastPage) {
                        flash.warning('Página não existe', 'Aviso!');
                        return;
                    }

                    /**
                     * Página abaixo de 1
                     */
                    if (goTo < 1) {
                        flash.warning('Página não existe', 'Aviso!');
                        return;
                    }
                    scope._currentPage = goTo;
                    scope.getPaginatorFilter(scope.paginator._perPage, scope._currentPage);
                };

                //Filtra quantidade de resultados
                scope.filterPagePaginator = function() {
                    if (APP.debug) {
                        console.log("filter page paginator ");
                    }
                    scope.setPaginatorPerPage(scope.paginator._perPage);
                    if (scope._total > 0) {
                        scope._currentPage = 1;
                        scope.getPaginatorFilter(scope.paginator._perPage, 1);
                    }
                };
            }
        };
    }
]);

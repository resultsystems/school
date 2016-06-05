/**
 * Modal Directive
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
angular.module('SchoolApp').directive('modal', ['APP',

    function(APP) {
        return {
            templateUrl: APP.domainViewPath + 'directives/modal.html',
            restrict: 'E',
            transclude: true,
            replace: true,
            scope: true,
            link: function postLink(scope, element, attrs) {
                scope.title = attrs.title;
                scope.modalClass = (typeof(attrs.modalClass) === "undefined") ? '' : attrs.modalClass;
                scope.modalClose = (typeof(attrs.modalClose) === "undefined") ? false : true;
                scope.$watch(attrs.visible, function(value) {
                    if (value == true)
                        $(element).modal('show');
                    else
                        $(element).modal('hide');
                });

                $(element).on('shown.bs.modal', function() {
                    scope.$apply(function() {
                        scope.$parent[attrs.visible] = true;
                    });
                });

                $(element).on('hidden.bs.modal', function() {
                    scope.$apply(function() {
                        scope.$parent[attrs.visible] = false;
                    });
                });
            }
        };
    }
]);

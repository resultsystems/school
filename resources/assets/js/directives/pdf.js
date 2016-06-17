// jQuery needed, uses Bootstrap classes, adjust the path of templateUrl
angular.module('SchoolApp').directive('pdfDownload', function() {
    return {
        restrict: 'E',
        templateUrl: '/views/directives/pdf.html',
        scope: true,
        link: function(scope, element, attr) {
            var anchor = element.children()[0];
 
            // When the download starts, disable the link
            scope.$on('download-start', function() {
                $(anchor).attr('disabled', 'disabled');
            });
 
            // When the download finishes, attach the data to the link. Enable the link and change its appearance.
            scope.$on('downloaded', function(event, data) {
                $(anchor).attr({
                    href: 'data:application/pdf;base64,' + data,
                    download: attr.filename
                })
                    .removeAttr('disabled')
                    .text('Salvar')
                    .removeClass('btn-primary')
                    .addClass('btn-success');
 
                 //Also overwrite the download pdf function to do nothing.
                scope.downloadPdf = function() {
                };
            });
        },
        controller: ['$scope', '$attrs', '$http', 'Restful', function($scope, $attrs, $http, Restful) {
            $scope.downloadPdf = function() {
                $scope.$emit('download-start');
                Restful.get('billet/'+$attrs.billetId+'/pdf', function(data) {
                    var pom = document.createElement('a');
                    pom.setAttribute('href', 'data:application/pdf;base64,' + data);
                    pom.setAttribute('download', 'boleto.pdf');
                    pom.style.display = 'none';
                    document.body.appendChild(pom);
                    pom.click();
                    document.body.removeChild(pom); 
                    //$scope.$emit('downloaded', data);
                });
            };
        }] 
    }
});
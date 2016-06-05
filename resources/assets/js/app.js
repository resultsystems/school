/*
* @Author: Leandro Henrique Reis <henrique@henriquereis.com>
* @Date:   2016-06-04 21:09:08
* @Last Modified by:   Leandro Henrique Reis
* @Last Modified time: 2016-06-04 21:09:22
*/

'use strict';
var SchoolApp = angular.module("SchoolApp", [
    "ui.router", 
    "ui.bootstrap", 
    "ngSanitize",
    "StatesAndCities",
    "Postcode",
    "OrderService",
    "frapontillo.bootstrap-switch",
    "Restful",
    "ngFileUpload",
    "PaginatorData"
]).constant('APP', {
    name: 'School Version 1',
    debug: true,
    domainViewPath: '/views/'
}).constant('API', {
    path: '/api/',
    version: 'v1/',
});


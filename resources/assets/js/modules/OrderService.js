/*
* @Author: Leandro Henrique Reis <emtudo@gmail.com>
* @Date:   2016-05-18 10:34:27
* @Last Modified by:   Leandro Henrique Reis
* @Last Modified time: 2016-06-04 19:50:40
*/
angular.module('OrderService', []).service('OrderService', [
    function() {
        var order = {};
        return {
            get: function(modulo, item) {
                if (order[modulo] == undefined) {
                    order[modulo] = {};
                }

                if (order[modulo][item] == undefined) {
                    order[modulo][item] = -1;
                }

                order[modulo][item] += 1;

                if (order[modulo][item] === 2) {
                    delete order[modulo][item];

                    return getOrder(order[modulo]);
                }

                return getOrder(order[modulo]);
            },
            byOrder: function(modulo, item) {
                if (order[modulo] == undefined) {
                    return 'random';
                }

                if (order[modulo][item] == undefined) {
                    return 'random';
                }

                if (order[modulo][item] === 0) {
                    return 'asc';
                }

                return 'desc';
            }
        };

        function getOrder(modulo) {
            var order = [];
            for (var key in modulo) {
                if (modulo.hasOwnProperty(key)) {
                    if (modulo[key] === 1) {
                        order.push('-' + key);
                    } else {
                        order.push(key);
                    }
                }
            }
            return order;
        }
    }
]);

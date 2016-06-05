/**
 * Billet Service
 *
 * @author Leandro Henrique <emtudo@gmail.com>
 */
angular.module('SchoolApp').service('BilletService', ['Restful',
    function(Restful) {
        return {
            pay: function(billet, success) {
                flash.confirm(function() {
                    billet.discharge_date=moment(billet.datePay, 'DD/MM/YYYY').format('YYYY-MM-DD');
                    Restful.put('billet/'+billet.id+'/pay', billet, function(data) {
                        return success(data);
                    }, function(response) {
                        return showErrors(response.data);
                    });
                }, 'Quitar o boleto com esta data: ' + billet.datePay+'?', 'Quitar!', 'Sim, continuar', 'Cancelar');
            },
            prepareBillet: function(billet) {
                var today=moment().format('DD/MM/YYYY');

                billet.new_due_date=moment(billet.new_due_date).format('DD/MM/YYYY');
                billet.datePay=today;
                billet.isSettled=isSettled(billet.discharge_date);
                billet.currency=formats(billet.amount).currency();
                if (billet.discharge_date) {
                    billet.discharge_date=moment(billet.discharge_date).format('DD/MM/YYYY');
                }

                return billet;
            },
            prepareBillets: function(billets) {
                for (var i = billets.length - 1; i >= 0; i--) {
                    billets[i]=this.prepareBillet(billets[i]);
                }

                return billets;
            }
        };
    }
]);
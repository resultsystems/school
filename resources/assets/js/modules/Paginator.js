/*
* @Author: Leandro Henrique Reis <emtudo@gmail.com>
* @Date:   2016-05-30 19:57:34
* @Last Modified by:   Leandro Henrique Reis
* @Last Modified time: 2016-06-04 19:50:40
*/
angular.module("PaginatorData", [])
.service("Paginator", [
function() {
        return {
            setPaginationData: function(all, filtered, pagination) {
                var chunk    = _.chunk(filtered, pagination.perPage);

                var entity={
                    all: all,
                    filtered: filtered,
                    list: chunk[0],
                    paginated: chunk
                };

                pagination.currentPage = 1;
                pagination.totalItems = Object.keys(filtered).length;
                pagination.totalPages = Math.ceil(Object.keys(filtered).length / pagination.perPage);
                pagination.pageNumbers = _.range(1, pagination.totalPages+1);
                pagination.currentItem=(pagination.currentPage*pagination.perPage)-pagination.perPage+1;
                pagination.lastCurrentPage=0;
                if (Object.keys(chunk).length>0) {
                    pagination.lastCurrentPage=chunk[0].length;
                }
                return {'entity': entity, 'pagination': pagination};
            },
            page: function(entity, page, pagination)
            {
                if (entity.paginated[page-1]==undefined) {
                    entity.list=[];
                    pagination.currentItem=0;
                    pagination.lastCurrentPage=0;
                    
                    return {'entity': entity, 'pagination': pagination};
                }
                pagination.currentPage = page;

                entity.list = entity.paginated[page-1];
                pagination.currentItem=(pagination.currentPage*pagination.perPage)-pagination.perPage+1;                          
                pagination.lastCurrentPage=(pagination.currentPage*pagination.perPage)-pagination.perPage+entity.paginated[page-1].length;

                return {'entity': entity, 'pagination': pagination};
            },
            next: function(entity, pagination) {
                if(pagination.currentPage == pagination.totalPages)
                {
                    return false;
                }

                pagination.currentPage = pagination.currentPage+1;

                entity.list = entity.paginated[pagination.currentPage-1];
                pagination.currentItem=(pagination.currentPage*pagination.perPage)-pagination.perPage+1;
                pagination.lastCurrentPage=(pagination.currentPage*pagination.perPage)-pagination.perPage+entity.paginated[page-1].length;

                return {'entity': entity, 'pagination': pagination};
            },
            previous: function(entity, pagination)
            {
                if(pagination.currentPage == 1)
                {
                    return false;
                }

                pagination.currentPage = pagination.currentPage-1;

                entity.list = entity.paginated[pagination.currentPage-1];
                pagination.currentItem=(pagination.currentPage*pagination.perPage)-pagination.perPage+1;
                pagination.lastCurrentPage=(pagination.currentPage*pagination.perPage)-pagination.perPage+entity.paginated[page-1].length;

                return {'entity': entity, 'pagination': pagination};
            }
        };
}]);
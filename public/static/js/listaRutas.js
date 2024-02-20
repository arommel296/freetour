$(function () {
    $("#paginacion").pagination({
        dataSource: '/api/ruta/paginado',
        itemsOnPage: 9,
        cssStyle: 'light-theme'
    });
})
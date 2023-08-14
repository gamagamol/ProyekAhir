const report = {
    dataTable: function (table, url, columns) {
        return $(table).DataTable({
            lengthChange: false,
            pageLength: 5,
            ordering: false,
            scrollX: true,
            autoWidth: true,
            processing: true,
            serverSide: true,
            destroy: true,
            order: [],
            ajax: {
                url: url,
                type: 'GET',
            },
            columns,
        });
    },
};

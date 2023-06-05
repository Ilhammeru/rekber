var dt;

const init = () => {
    dt = $('#table-deposit').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: {
            url: app_url + '/deposit/ajax/' + status,
        },
        columns: [
            {data: 'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: '5%',
                className: 'text-center'
            },
            {data: 'amount', name: 'amount'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', className: 'text-center', orderable: false},
        ],
        order: [[0, 'desc']],
    });
}

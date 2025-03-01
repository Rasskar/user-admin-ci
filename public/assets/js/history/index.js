$(document).ready(function() {
    let table = $('#usersTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/profiles/datatable",
            "type": "GET",
            "dataSrc": function(response) {
                $('.alert-container').empty();

                return response.data;
            },
            "error": function(xhr, error, thrown) {
                let errorObject = JSON.parse(xhr.responseText);

                $('.alert-container').html(`<div class="alert alert-danger">${errorObject.message}</div>`);
            }
        },
        "columns": [
            { "data": "id" },
            {
                "data": "username",
                "render": function(data, type, row) {
                    return `<a href="/profile/${row.id}" class="user-link">${data}</a>`;
                }
            },
            { "data": "email" },
            { "data": "status" }
        ],
        "order": [[0, "desc"]],
        "columnDefs": [
            { "orderable": false, "targets": [1, 2, 3] }
        ],
        "language": {
            "paginate": {
                "previous": "«",
                "next": "»"
            },
            "loadingRecords": "Loading...",
            "zeroRecords": "No users found",
            "lengthMenu": "_MENU_",
            "search": "<i class='bx bx-search'></i>"
        },
        "dom": 'tp'
    });
});
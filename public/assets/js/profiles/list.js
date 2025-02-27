$(document).ready(function() {
    let table = $('#usersTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/profiles/datatable",
            "type": "GET",
            "dataSrc": function(response) {
                console.log(response);

                return response.data;
            },
            "error": function(xhr, error, thrown) {
                console.log(error);
                console.log(thrown);
                console.log(xhr);


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
        "dom": '<"table-head-container"<"search-container"f><"length-container"l>>' +
            '<"table-container"t>' +
            '<"pagination-container"p>'
    });
});
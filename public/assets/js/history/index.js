$(document).ready(function () {
    let table = $('#historyTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/history/datatable",
            "type": "GET",
        },

        "columns": [
            {
                "data": "username",
                "render": function(data, type, row) {
                    return `<a href="/profile/${row.id}" class="user-link">${data}</a>`;
                }
            },
            { "data": "action" },
            { "data": "model" },
            { "data": "model_id" },
            {
                "data": "old_data",
                "render": function (data) {
                    return (data && Object.keys(data).length > 0) ? JSON.stringify(data) : "";
                }
            },
            {
                "data": "new_data",
                "render": function (data) {
                    return (data && Object.keys(data).length > 0) ? JSON.stringify(data) : "";
                }
            },
            { "data": "ip" },
            { "data": "created_at" }

        ],
        "ordering": false,
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

    let wsHost = window.location.hostname || "localhost";
    let wsPort = 9501;
    let socket = new WebSocket(`ws://${wsHost}:${wsPort}`);

    socket.onopen = function () {
        console.log("WebSocket connected");
    };

    socket.onmessage = function (event) {
        try {
            let data = JSON.parse(event.data);

            if (data.action === "refresh" && data.channel === "history") {
                table.ajax.reload(null, false);
            }
        } catch (error) {
            console.error("WebSocket message parsing error:", error);
        }
    };

    socket.onclose = function () {
        console.log("WebSocket disconnected.");
    };

    socket.onerror = function (error) {
        console.error("WebSocket error", error);
    };
});
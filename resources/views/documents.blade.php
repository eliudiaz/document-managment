<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documents Editor</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{asset('js/jquery-loader.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script type="text/javascript">
        let deleteDoc = (path) => {
            if (confirm('Are you sure to delete this record?')) {
                $.loader.open();
                $.ajax({
                    url: path,
                    type: 'DELETE',
                    data: {_token: '{{csrf_token()}}'},
                    success: function () {
                        getDocuments();
                    }
                });
            }
        };
        let getDocuments = (parameters) => {
            $.loader.open();
            let path = parameters ? parameters.join('&') : '';
            $.getJSON('/api/documents?' + path, function (data) {
                $('#documents tbody tr').remove();
                let start = '<tr>';
                data.map((r) => {
                    return start + `<td>${r.title}</td>
                            <td>${r.author}</td>
                            <td>${r.tags}</td>
                            <td>${r.created_at}</td>
                            <td>${r.updated_at}</td>
                            <td>
                                <a href="/documents/${r.id}/edit" class="edit" data-toggle="modal">
                                <i class="material-icons data-toggle="tooltip title="Edit">&#xE254;</i></a>
                                            <a href="javascript:deleteDoc('/api/documents/${r.id}')" class="delete">
                                            <i class="material-icons"  title="Delete">&#xE872;</i></a>
                            </td>`;
                }).forEach((r) => {
                    $('#documents').append(r);
                });
                $.loader.close();
            });
        };
        $(document).ready(function () {
            getDocuments();

            $('#search').on('click', () => {
                getDocuments(['author=' + $('#author').val(),
                    'date_type=' + $('#date_type').val(),
                    'from=' + $('#from').val(),
                    'to=' + $('#from').val(),
                ]);

            });
        });
        $('[data-toggle="tooltip"]').tooltip();
        var checkbox = $('table tbody input[type="checkbox"]');
        $("#selectAll").click(function () {
            if (this.checked) {
                checkbox.each(function () {
                    this.checked = true;
                });
            } else {
                checkbox.each(function () {
                    this.checked = false;
                });
            }
        });
        checkbox.click(function () {
            if (!this.checked) {
                $("#selectAll").prop("checked", false);
            }
        });

    </script>
</head>
<body>
<div class="container">
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-6">
                    <h2>Manage <b>Documents</b></h2>
                </div>
                <div class="col-sm-6">
                    <a href="{{route('documents.create')}}" class="btn btn-success" data-toggle="modal"><i
                                class="material-icons">&#xE147;</i>
                        <span>New Document</span></a>
                </div>
            </div>
        </div>
        <div class="row" style="color: black;">
            <div class="col-sm-6">
                <h4>Filter</h4>
                <div>
                    <div class="form-group">
                        <label>Author</label>
                        <input id="author" type="text" placeholder="Author name"/>
                    </div>
                    <div class="form-group">
                        <label>Date type:</label>
                        <select id="date_type">
                            <option value="created_at">Creation Date</option>
                            <option value="created_at">Last Update Date</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>From</label>
                        <input id="from" type="date" placeholder="Author name"/>
                        <label>To</label>
                        <input id="to" type="date" placeholder="Author name"/>
                    </div>
                </div>
                <button id="search" type="button" class="btn btn-success">Search</button>
            </div>

        </div>
        <table id="documents" class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Tags</th>
                <th>Created At</th>
                <th>Last Update</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


</body>
</html>
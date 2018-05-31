<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-markdown.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.tag-editor.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/bootstrap-markdown.js') }}"></script>
    <script src="{{ asset('js/ace.js') }}"></script>
    <script src="{{ asset('js/marked.js') }}"></script>
    <script src="{{ asset('js/jquery.caret.min.js') }}"></script>
    <script src="{{ asset('js/jquery.tag-editor.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            $('#tags').tagEditor({
                delimiter: ', ', placeholder: 'Enter tags ...'
            }).css('display', 'block').attr('readonly', true);

            // Activate tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Select/Deselect checkboxes
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
        });
    </script>
</head>
<body>
<form id="deleteForm" role="form"
      action="{{ $action }}"
      class="ui form col-md-12" method="post"
      enctype="multipart/form-data">
    @if(isset($record['id']))
        <input name="_method" type="hidden" value="PUT">
    @endif
    <div class="container">
        <div class="table-wrapper">
            <div class="form-group">
                <label for="title">Title</label>
                <input class="form-control" name="title" placeholder="Title?" type="text"
                       value="{{ old('title',optional($record)['title'])  }}">
            </div>
            <div class="form-group">
                <label for="title">Tags</label>
                <input type="text" name="tags" id="tags" placeholder="Tags"
                       value="{{ old('tags',optional($record)['tags'])  }}">
            </div>
            <textarea id="content" name="content"
                      data-provide="markdown">{!!  old('content',optional($record)['content']) !!}</textarea>

            <hr>
            {{ csrf_field() }}
            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{route('documents.index')}}" class="btn alert-danger">Cancel</a>
            @if(isset($record['id']))
                <a target="_blank" href="{{ '/api/documents/'.$record['id'].'/download' }}" class="btn alert-danger">Download
                    as PDF</a>
            @endif
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $("#content").markdownEditor({
            preview: true,
            onPreview: function (content, callback) {
                callback(marked(content));
            },
        });
    });
</script>
</body>
</html>
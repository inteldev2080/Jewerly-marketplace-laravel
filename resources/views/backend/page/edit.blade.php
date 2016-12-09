@extends('backend.layouts.app', ['activePage' => 'page', 'title' => 'Edit Page', 'navName' => 'Table List', 'activeButton' => 'blog'])

@section('content')
<div class="page-header">
    <div class="row align-items-end">
        <h1 class="page-header-title">Edit Page</h1>
    </div>
    <!-- End Row -->
</div>
    <form action="{{ route('backend.page.update', $page->id) }}" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
                @csrf
                @method('PUT')
                    <div class="card col-md-12 mb-4">
                        <!-- Header -->
                        <div class="card-header">
                            <h4 class="card-header-title mb-0">Page information</h4>
                        </div>
                        <!-- End Header -->
                        <div class="card-body row">
                            @include('includes.validation-form')

                            <div class="col-md-12 mb-2">
                                <label for="name">Name:</label>
                                <input type="text" name="name" id="name" value="{{ $page->name }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="name">Slug:</label>
                                <input type="text" name="slug" id="slug" value="{{ $page->slug }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-12 mb-2">
                                <a href={{url($page->url)}}>{{url($page->url)}}</a>
                            </div>

                            <div class="col-md-12">
                                <label for="desc">Post:</label>
                                <textarea name="post" id="desc" rows="3" class="form-control">
                                    {{ $page->post }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card col-md-12">
                        <!-- Header -->
                        <div class="card-header">
                            <h4 class="card-header-title mb-0">Meta information</h4>
                        </div>
                        <!-- End Header -->
                        <div class="card-body row">
                            <div class="mb-2">
                                <label for="meta_title" class="w-100 mb-2">Meta Title:</label>
                                <input type="text" name="meta_title" id="meta_title" value="{{ $page->meta_title }}" class="form-control">
                            </div>
                            <div class="">
                                <label for="meta_description" class="w-100 mb-2">Meta Description:</label>
                                <textarea name="meta_description" id="meta_description" rows="6" class="form-control">{{ $page->meta_description }}</textarea>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                <!-- Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-header-title mb-0">Status</h3>
                        <small class="text-muted">Published: 2 days ago</small>
                    </div>
                    <div class="card-body">
                        {{ date('F d, Y, h:i:s A', strtotime($page->created_at)) }}
                        <br />
                        <br />
                        Author: {{ $page->author->first_name . " " . $page->author->last_name }}
                    </div>
                </div>
                <!-- End Card -->

                <!-- Card -->
                <div class="card mb-3 mb-4">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title mb-0">Organization</h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        <div class="col-12 mb-4">
                        <label class="mb-2" for="">Status</label>
                            <select class="selectpicker w-100" name="status">
                                <option value="1" @if($page->status == 1) selected @endif >Published</option>
                                <option value="2" @if($page->status == 2) selected @endif>Draft</option>
                                <option value="3" @if($page->status == 3) selected @endif>Pending Review</option>
                            </select>
                        </div>
                        <div class="col-12 mb-4">
                            <label for="category" class="mb-2 w-100">Parent:</label>
                            <select class="selectpicker" name="parent_id" data-live-search="true" data-container="body">
                            <option value="0" @if($page->parent_id == 0) selected @endif >None</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}" @if ($parent->id == $page->parent_id) selected @endif>
                                    {{ $parent->name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
                <!-- Card -->
                <!-- End Card -->
            </div>
        </div>

        <div class="position-fixed start-50 bottom-0 translate-middle-x w-100 zi-99 mb-3" style="max-width: 40rem;">
            <!-- Card -->
            <div class="card card-sm bg-dark border-dark mx-2">
                <div class="card-body">
                <div class="row justify-content-center justify-content-sm-between">
                    <div class="col">
                    <button type="button" class="btn btn-danger">Delete</button>
                    </div>
                    <!-- End Col -->

                    <div class="col-auto">
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-light">Unpublish</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                    </div>
                    <!-- End Col -->
                </div>
                <!-- End Row -->
                </div>
            </div>
            <!-- End Card -->
        </div>
    </form>

    <div id="fileManagerContainer"></div>

    <div id='ajaxCalls'>
    </div>
@endsection

@section('js_content')
    <script>
        $(document).ready(function() {
            $('#desc').trumbowyg();
            $('#meta_description').trumbowyg();
        })
        $(".imgAdd").click(function() {
            $(this).closest(".row").find('.imgAdd').before(
                '<div class="col-sm-2 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>'
            );
        });
        $(document).on("click", "i.del", function() {
            $(this).parent().remove();
        });
        $(function() {
            $(document).on("change", ".uploadFile", function() {
                var uploadFile = $(this);
                var files = !!this.files ? this.files : [];
                if (!files.length || !window.FileReader)
                    return; // no file selected, or no FileReader support

                if (/^image/.test(files[0].type)) { // only image file
                    var reader = new FileReader(); // instance of the FileReader
                    reader.readAsDataURL(files[0]); // read the local file

                    reader.onloadend = function() { // set image data as background of div
                        //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                        uploadFile.closest(".imgUp").find('.imagePreview').css("background-image",
                            "url(" + this.result + ")");
                    }
                }

            });
            $('.select2').select2({

                tags: true,
                maximumSelectionLength: 100,
                tokenSeparators: [','],
                placeholder: "Select or type keywords",
            })


            $('#getFileManager').click(function () {
                $.ajax({
                    url: "{{ route('backend.file.show') }}",
                    success: function (data) {
                        if (!$.trim($('#fileManagerContainer').html()))
                            $('#fileManagerContainer').html(data);

                        $('#fileManagerModal').modal('show');

                        const getSelectedItem = function (selectedId, filePath) {

                            $('#fileManagerId').val(selectedId);
                            $('#fileManagerPreview').attr('src', filePath);
                        }

                        setSelectedItemsCB(getSelectedItem, $('#fileManagerId').val() == '' ? [] : [$('#fileManagerId').val()], false);
                    }
                })
            });
        });
    </script>
@endsection

<!DOCTYPE html>
<html lang="ko">
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    @include('admin.components.head')
</head>

<body>
<div id="wrap">
    <div class="admin-container">
        <header id="header">
            @include('admin.components.snb')
        </header>

        <div class="admin-wrap">
            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="title-wrap col-group">
                <h2 class="main-title">
                    공지사항 상세
                </h2>
            </div>

            <form id="form" action="{{ route('admin.announcementUpdate', $item) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            상단 공지
                            <span class="red">*</span>
                        </p>
                        <div class="radio-wrap">
                            <div class="label-wrap col-group">
                                <label for="radio_item_1" class="radio-item">
                                    <input type="radio" name="is_featured" id="radio_item_1" value="1" class="form-radio" {{ $item->is_featured ? 'checked' : '' }}>
                                    <div class="checked-item col-group">
                                        <span class="radio-icon"></span>
                                        Y
                                    </div>
                                </label>
                                <label for="radio_item_2" class="radio-item">
                                    <input type="radio" name="is_featured" id="radio_item_2" value="0" class="form-radio" {{ !$item->is_featured ? 'checked' : '' }}>
                                    <div class="checked-item col-group">
                                        <span class="radio-icon"></span>
                                        N
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            제목
                            <span class="red">*</span>
                        </p>
                        <input type="text" name="title" class="form-input" id="title" value="{{ old('title', $item->title) }}"
                               placeholder="제목을 작성해주세요.">
                    </div>
                    <div class="form-item editor row-group">
                        <p class="item-default">
                            내용
                            <span class="red">*</span>
                        </p>
                        <div id="editor"></div>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            파일 첨부
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='image_upload' accept="image/*" name="file" style="display: none;">
                            <label for="image_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <div class="file-preview" id="image-preview"
                                 @if(!$item->file) style="display: none" @endif>
                                <p class="file-name" id="image-filename">
                                    @if($item->file)
                                        {{$item->file_name}}
                                    @endif
                                </p>
                                <button type="button" class="file-del-btn" id="remove-image-btn">
                                    <i class="xi-close"></i>
                                </button>
                            </div>
                            <input type="hidden" name="remove_file" id="remove_file" value="0">
                        </div>
                    </div>
                </div>

                <div class="form-btn-wrap col-group">
                    <a href="{{ route('admin.announcementIndex') }}" class="form-prev-btn">
                        목록으로
                    </a>
                    <button class="form-prev-btn" type="submit" id="submit">
                        수정
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    let toolbarOptions = [
        [{'size': ['small', false, 'large', 'huge']}],
        ['bold', 'italic', 'underline', 'strike'],
        ['image'],
        [{'align': []}, {'color': []}, {'background': []}],
        ['clean']
    ];

    let quill = new Quill('#editor', {
        modules: {
            toolbar: {
                container: toolbarOptions,
                handlers: {
                    'image': imageHandler
                }
            }
        },
        theme: 'snow'
    });

    function imageHandler() {
        let self = this;
        let fileInput = document.createElement('input');
        fileInput.setAttribute('type', 'file');
        fileInput.setAttribute('accept', 'image/*');
        fileInput.addEventListener('change', function() {
            let file = this.files[0];
            let formData = new FormData();
            formData.append('image', file);

            fetch("{{ route('admin.uploadFile') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            })
                .then(response => response.json())
                .then(data => {
                    let url = data.url;
                    let range = quill.getSelection();
                    quill.insertEmbed(range.index, 'image', url);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
        fileInput.click();
    }

    quill.root.innerHTML = `{!! $item->content !!}`;

    document.getElementById('form').addEventListener('submit', function (e) {
        let contentInput = document.createElement('input');
        contentInput.setAttribute('type', 'hidden');
        contentInput.setAttribute('name', 'content');
        contentInput.setAttribute('value', quill.root.innerHTML);
        this.appendChild(contentInput);
    });

    document.getElementById('image_upload').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('image-preview').style.display = 'block';
            document.getElementById('image-filename').textContent = file.name;
            document.getElementById('remove_file').value = 0;
        }
    });

    document.getElementById('remove-image-btn').addEventListener('click', function () {
        document.getElementById('image-preview').style.display = 'none';
        document.getElementById('image_upload').value = '';
        document.getElementById('remove_file').value = 1;
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

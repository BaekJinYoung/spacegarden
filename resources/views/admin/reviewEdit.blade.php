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
                    고객후기 상세
                </h2>
            </div>

            <form id="form" action="{{route("admin.reviewUpdate", $item)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            메인 노출
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
                        <input type="text" id="title" name="title" class="form-input"
                               value="{{old('title', $item->title)}}" placeholder="제목을 입력하세요">
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
                            이미지
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='image_upload' accept="image/*" name="image" style="display: none;">
                            <label for="image_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <div class="file-preview" id="image-preview"
                                 @if(!$item->image) style="display: none" @endif>
                                <p class="file-name" id="image-filename">
                                    @if($item->image)
                                        {{$item->image_name}}
                                    @endif
                                </p>
                                <button type="button" class="file-del-btn" id="remove-image-btn">
                                    <i class="xi-close"></i>
                                </button>
                            </div>
                            <input type="hidden" name="remove_image" id="remove_image" value="0">
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            첨부파일
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='file_upload' accept="image/*" name="file" style="display: none;">
                            <label for="file_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <div class="file-preview" id="file-preview"
                                 @if(!$item->file) style="display: none" @endif>
                                <p class="file-name" id="file-filename">
                                    @if($item->file)
                                        {{$item->file_name}}
                                    @endif
                                </p>
                                <button type="button" class="file-del-btn" id="remove-file-btn">
                                    <i class="xi-close"></i>
                                </button>
                            </div>
                            <input type="hidden" name="remove_file" id="remove_file" value="0">
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <div class="form-group">
                            <label for="filter">필터 선택 <span class="red">*</span> </label>
                            <select id="filter" name="filter_category" class="form-control">
                                <option value="전체 정리수납" {{ old('filter_category', $item->filter_category) == '전체 정리수납' ? 'selected' : '' }}>
                                    전체 정리수납
                                </option>
                                <option value="부분 정리수납" {{ old('filter_category', $item->filter_category) == '부분 정리수납' ? 'selected' : '' }}>
                                    부분 정리수납
                                </option>
                                <option value="원스톱 토탈서비스" {{ old('filter_category', $item->filter_category) == '원스톱 토탈서비스' ? 'selected' : '' }}>
                                    원스톱 토탈서비스
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <div class="form-group">
                            <label for="filter">필터 선택 <span class="red">*</span> </label>
                            <select id="filter" name="filter_area" class="form-control">
                                <option value="원룸" {{ old('filter_area', $item->filter_area) == '원룸' ? 'selected' : '' }}>
                                    원룸
                                </option>
                                <option value="10평대" {{ old('filter_area', $item->filter_area) == '10평대' ? 'selected' : '' }}>
                                    10평대
                                </option>
                                <option value="20평대" {{ old('filter_area', $item->filter_area) == '20평대' ? 'selected' : '' }}>
                                    20평대
                                </option>
                                <option value="30평대" {{ old('filter_area', $item->filter_area) == '30평대' ? 'selected' : '' }}>
                                    30평대
                                </option>
                                <option value="40평대" {{ old('filter_area', $item->filter_area) == '40평대' ? 'selected' : '' }}>
                                    40평대
                                </option>
                                <option value="50평대 이상" {{ old('filter_area', $item->filter_area) == '50평대 이상' ? 'selected' : '' }}>
                                    50평대 이상
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-btn-wrap col-group">
                    <a href="{{route("admin.reviewIndex")}}" class="form-prev-btn">
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
            document.getElementById('remove_image').value = 0;
        }
    });

    document.getElementById('remove-image-btn').addEventListener('click', function () {
        document.getElementById('image-preview').style.display = 'none';
        document.getElementById('image_upload').value = '';
        document.getElementById('remove_image').value = 1;
    });

    document.getElementById('file_upload').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('file-preview').style.display = 'block';
            document.getElementById('file-filename').textContent = file.name;
            document.getElementById('remove_file').value = 0;
        }
    });

    document.getElementById('remove-file-btn').addEventListener('click', function () {
        document.getElementById('file-preview').style.display = 'none';
        document.getElementById('file_upload').value = '';
        document.getElementById('remove_file').value = 1;
    });
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

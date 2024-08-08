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
                    고객후기 등록
                </h2>
            </div>
            <form id="form" action="{{route('admin.reviewStore')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            메인 노출
                            <span class="red">*</span>
                        </p>
                        <div class="radio-wrap">
                            <div class="label-wrap col-group">
                                <label for="radio_item_1" class="radio-item">
                                    <input type="radio" name="is_featured" id="radio_item_1" value="1" class="form-radio">
                                    <div class="checked-item col-group">
                                        <span class="radio-icon"></span>
                                        Y
                                    </div>
                                </label>
                                <label for="radio_item_2" class="radio-item">
                                    <input type="radio" name="is_featured" id="radio_item_2" value="0" class="form-radio">
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
                        <input type="text" id="title" name="title" class="form-input" value="{{old('title')}}" placeholder="제목을 입력하세요">
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
                            <div class="file-preview" id="image-preview" style="display: none">
                                <p class="file-name" id="image-filename"></p>
                                <button type="button" class="file-del-btn" id="remove-image-btn">
                                    <i class="xi-close"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            파일 첨부
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='file_upload' accept="image/*" name="file" style="display: none;">
                            <label for="file_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <div class="file-preview" id="file-preview" style="display: none">
                                <p class="file-name" id="file-filename"></p>
                                <button type="button" class="file-del-btn" id="remove-file-btn">
                                    <i class="xi-close"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <div class="form-group">
                            <label for="filter">필터 선택 <span class="red">*</span> </label>
                            <select id="filter" name="filter_category" class="form-control">
                                <option value=0>전체 정리수납</option>
                                <option value=1>부분 정리수납</option>
                                <option value=2>원스톱 토탈서비스</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <div class="form-group">
                            <label for="filter">필터 선택 <span class="red">*</span> </label>
                            <select id="filter" name="filter_area" class="form-control">
                                <option value=0>원룸</option>
                                <option value=1>10평대</option>
                                <option value=2>20평대</option>
                                <option value=3>30평대</option>
                                <option value=4>40평대</option>
                                <option value=5>50평대 이상</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-btn-wrap col-group">
                    <a href="{{route("admin.reviewIndex")}}" class="form-prev-btn">
                        목록으로
                    </a>
                    <button class="form-prev-btn" type="submit" id="submit">
                        등록
                    </button>
                    <button class="form-submit-btn" id="submitContinue" name="continue" type="submit" value="1">
                        등록 후 계속
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
            formData.append('iamge', file);

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

    ['submit', 'submitContinue'].forEach(function (index) {
        let button = document.getElementById(index);
        button.addEventListener('click', function () {
            let form = document.getElementById('form');

            let hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'content');
            hiddenInput.setAttribute('id', 'content');
            hiddenInput.value = quill.root.innerHTML;
            form.appendChild(hiddenInput);

            let formData = new FormData(form);
            if (button.value === "continue") {
                fetchUtil("{{ route('admin.reviewStore') }}", formData, "{{ route('admin.reviewCreate') }}", "등록되었습니다.");
            } else {
                fetchUtil("{{ route('admin.reviewStore') }}", formData, "{{ route('admin.reviewIndex') }}");
            }
        });
    });

    document.getElementById('image_upload').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('image-preview').style.display = 'block';
            document.getElementById('image-filename').textContent = file.name;
        }
    });

    document.getElementById('remove-image-btn').addEventListener('click', function () {
        document.getElementById('image_upload').value = '';
        document.getElementById('image-preview').style.display = 'none';
        document.getElementById('remove_image').value = '1';
    });

    document.getElementById('file_upload').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('file-preview').style.display = 'block';
            document.getElementById('file-filename').textContent = file.name;
        }
    });

    document.getElementById('remove-file-btn').addEventListener('click', function () {
        document.getElementById('file_upload').value = '';
        document.getElementById('file-preview').style.display = 'none';
        document.getElementById('remove_file').value = '1';
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

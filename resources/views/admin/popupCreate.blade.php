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
                    팝업 등록
                </h2>
            </div>
            <form action="{{route('admin.popupStore')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            제목
                            <span class="red">*</span>
                        </p>
                        <input type="text" name="title" class="form-input" id="title" value="{{old('title')}}"
                               placeholder="제목을 입력하세요">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            이미지
                            <span class="red">*</span>
                        </p>
                        <input type="hidden" name="remove_image" id="remove_image" value="1">
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
                            링크(선택)
                        </p>
                        <input type="text" name="link" class="form-input" id="link" value="{{old('link')}}"
                               placeholder="링크를 입력하세요(https:// 포함), 선택사항">
                    </div>
                </div>
                <div class="form-btn-wrap col-group">
                    <a href="{{route("admin.popupIndex")}}" class="form-prev-btn">
                        목록으로
                    </a>
                    <button class="form-prev-btn" type="submit">
                        등록
                    </button>
                    <button class="form-submit-btn" name="continue" type="submit" value="1">
                        등록 후 계속
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('image_upload').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('image-preview').style.display = 'block';
            document.getElementById('image-filename').textContent = file.name;
            document.getElementById('remove_image').value = 0;
        }
    });

    document.getElementById('remove-image-btn').addEventListener('click', function () {
        document.getElementById('image_upload').value = '';
        document.getElementById('image-preview').style.display = 'none';
        document.getElementById('remove_image').value = 1;
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

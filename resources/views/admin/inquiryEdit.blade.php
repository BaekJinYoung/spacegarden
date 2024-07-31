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
                    문의하기 상세
                </h2>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            작성일시
                        </p>
                        <input type="text" value="{{$item->created_at}}">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            성함
                        </p>
                        <input type="text" value="{{$item->name}}">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            연락처
                        </p>
                        <input type="text" value="{{$item->contact}}">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            이메일
                        </p>
                        <input type="text" value="{{$item->email}}">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            상담분야
                        </p>
                        <input type="text" value="{{$item->inquiry_category}}">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            문의내용
                        </p>
                        <input type="text" value="{{$item->message}}">
                    </div>
                </div>

                <div class="form-btn-wrap col-group">
                    <a href="{{route("admin.inquiryIndex")}}" class="form-prev-btn">
                        목록으로
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

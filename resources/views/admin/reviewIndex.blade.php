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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        <li>{{ session('error') }}</li>
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="admin_photo_gallery">
                <div class="title-wrap col-group">
                    <div class="main-title-wrap col-group">
                        <h2 class="main-title">
                            고객후기
                        </h2>
                        <div class="top-btn-wrap">
                            <a href="{{route("admin.reviewCreate")}}" class="top-btn">
                                등록
                            </a>
                        </div>
                    </div>
                    <div class="filter_wrap">
                        <div class="filter_input_wrap">
                            <select id="pageCount" onchange="updatePageCount()">
                                <option value="8" {{ $perPage == 8 ? 'selected' : '' }}>1페이지에 8개까지</option>
                                <option value="16" {{ $perPage == 16 ? 'selected' : '' }}>1페이지에 16개까지</option>
                                <option value="24" {{ $perPage == 24 ? 'selected' : '' }}>1페이지에 24개까지</option>
                            </select>
                            <form action="{{route("admin.reviewIndex")}}" method="get">
                                <div class="search-wrap col-group">
                                    <input type="text" name="search" class="search-input" placeholder="제목을 입력하세요"
                                           value="{{ old('search', $search) }}">
                                    <button type="submit" class="search-btn">
                                        <i class="xi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="board-wrap col-group">
                    @if($items->isEmpty())
                        <div class="null-txt">
                            등록된 게시물이 없습니다.
                        </div>
                    @else
                        @foreach($items as $key => $item)
                            <div class="board-item">
                                <div class="img-box">
                                    @if($item->image)
                                        <img src="{{asset('storage/'.$item->image)}}" alt="">
                                    @else
                                        <img src="{{asset('images/certificate.png')}}" alt="">
                                    @endif
                                </div>
                                <div class="txt-box row-group">
                                    <p class="title">{{$item->title}}</p>
                                    <div class="btn-wrap col-group">
                                        <a href="{{route("admin.reviewEdit", $item->id)}}" class="btn">
                                            수정
                                        </a>
                                        <form action="{{route("admin.reviewDelete", $item->id)}}" method="post" onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn del-btn">
                                                삭제
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                @include('admin.components.pagination', ['paginator' => $items])
            </div>
        </div>
    </div>
</div>

<script>
    function updatePageCount() {
        var pageCount = document.getElementById('pageCount').value;
        window.location.href = '?perPage=' + pageCount;
    }
</script>
<script>
    function confirmDelete() {
        return confirm("정말로 삭제하시겠습니까?");
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

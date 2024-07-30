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

            <div class="table-wrap">
                <div class="title-wrap col-group">
                    <div class="main-title-wrap col-group">
                        <h2 class="main-title">
                            자주하는 질문
                        </h2>
                        <div class="top-btn-wrap">
                            <a href="{{route("admin.questionCreate")}}" class="top-btn">
                                등록
                            </a>
                        </div>
                    </div>
                    <div class="filter_wrap">
                        <div class="filter_input_wrap">
                            <select id="pageCount" onchange="updatePageCount()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>1페이지에 10개까지</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>1페이지에 20개까지</option>
                                <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>1페이지에 30개까지</option>
                            </select>
                            <form action="" method="get">
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
                <table class="admin-table">
                    <colgroup>
                        <col width="80%">
                        <col width="20%">
                    </colgroup>
                    <thead class="admin-thead">
                    <tr class="admin-tr">
                        <th class="admin-th">제목</th>
                        <th class="admin-th"></th>
                    </tr>
                    </thead>
                    <tbody class="admin-tbody">
                    @if($items->isEmpty())
                        <tr>
                            <td colspan="9">
                                <p class="null-txt">
                                    등록된 게시물이 없습니다.
                                </p>
                            </td>
                        </tr>
                    @else
                        @foreach($items as $key => $item)
                            <tr class="admin-tr">
                                <td class="admin-td">{{$item->title}}</td>
                                <td class="admin-td">
                                    <div class="btn-wrap col-group">
                                        <a href="{{route("admin.questionEdit", $item->id)}}" class="btn">
                                            상세
                                        </a>
                                        <form action="{{route("admin.questionDelete", $item->id)}}" method="post" onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn del-btn">
                                                삭제
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
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

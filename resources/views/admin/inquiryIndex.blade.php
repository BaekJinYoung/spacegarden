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
                            문의하기
                        </h2>
                    </div>
                    <div class="filter_wrap">
                        <div class="filter_input_wrap">
                            <select id="pageCount" onchange="updatePageCount()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>1페이지에 10개까지</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>1페이지에 20개까지</option>
                                <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>1페이지에 30개까지</option>
                            </select>
                        </div>
                    </div>
                </div>
                <table class="admin-table">
                    <colgroup>
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                    </colgroup>
                    <thead class="admin-thead">
                    <tr class="admin-tr">
                        <th class="admin-th">성함</th>
                        <th class="admin-th">연락처</th>
                        <th class="admin-th">이메일</th>
                        <th class="admin-th">상담분야</th>
                        <th class="admin-th">작성일</th>
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
                                <td class="admin-td">{{$item->name}}</td>
                                <td class="admin-td">{{$item->contact}}</td>
                                <td class="admin-td">{{$item->email}}</td>
                                <td class="admin-td">{{$item->inquiry_category}}</td>
                                <td class="admin-td">{{date('Y-m-d', strtotime($item->created_at))}}</td>
                                <td class="admin-td">
                                    <div class="btn-wrap col-group">
                                        <a href="{{route("admin.inquiryEdit", $item->id)}}" class="btn">
                                            상세
                                        </a>
                                        <form action="{{route("admin.inquiryDelete", $item->id)}}" method="post" onsubmit="return confirmDelete();">
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

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
                            팝업
                        </h2>
                        <div class="top-btn-wrap">
                            <a href="{{route("admin.popupCreate")}}" class="top-btn">
                                등록
                            </a>
                        </div>
                    </div>
                </div>

                <div class="board-wrap col-group">
                    @if($items->isEmpty())
                        <div class="null-txt">
                            등록된 팝업이 없습니다.
                        </div>
                    @else

                        @foreach($items as $key => $item)
                            <div class="board-item">
                                <div class="img-box">
                                    <img src="{{asset('storage/'.$item->image)}}" alt="">
                                </div>
                                <div class="txt-box row-group">
                                    <p class="title">{{$item->title}}
                                    </p>
                                    <p class="date col-group">
                                        @if($item->link)
                                            <a href="{{$item->link}}" class="btn" target="_blank">링크</a>
                                        @else
                                            <a class="btn" target="_blank">링크X</a>
                                        @endif
                                    </p>
                                    <div class="btn-wrap col-group">
                                        <a href="{{route("admin.popupEdit", $item->id)}}" class="btn">
                                            수정
                                        </a>
                                        <form action="{{route("admin.popupDelete", $item->id)}}" method="post" onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn del-btn">
                                                삭제
                                            </button>
                                        </form>
                                    </div>
                                    <div class="order_btn_wrap">
                                        {{--                                    <form--}}
                                        {{--                                        action="{{route("admin.popupMove", ['popup' => $item->id, 'direction' => 'left'])}}"--}}
                                        {{--                                        method="post">--}}
                                        {{--                                        @csrf--}}
                                        {{--                                        <button type="submit"><i class="xi-arrow-left"></i></button>--}}
                                        {{--                                    </form>--}}
                                        {{--                                    <form--}}
                                        {{--                                        action="{{route("admin.popupMove", ['popup' => $item->id, 'direction' => 'right'])}}"--}}
                                        {{--                                        method="post">--}}
                                        {{--                                        @csrf--}}
                                        {{--                                        <button type="submit"><i class="xi-arrow-right"></i></button>--}}
                                        {{--                                    </form>--}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

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
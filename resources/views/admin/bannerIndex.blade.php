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
                            메인 배너
                        </h2>
                        <div class="top-btn-wrap">
                            <a href="{{route("admin.bannerCreate")}}" class="top-btn">
                                등록
                            </a>
                        </div>
                    </div>
                </div>
                <div class="item_list item_list_2">
                    @if($items->isEmpty())
                        <div class="null-txt">
                            등록한 게시물이 없습니다.
                        </div>
                    @else
                        @foreach($items as $key => $item)
                            <ul class="item_list_ul">
                                <li>
                                    <div class="img_wrap">
                                        @if($item->is_video)
                                            <video controls class="img-fluid">
                                                <source src="{{ asset('storage/'.$item->image) }}" type="video/{{ pathinfo($item->image, PATHINFO_EXTENSION) }}">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <img src="{{ asset('storage/'.$item->image) }}" alt="이미지" class="img-fluid">
                                        @endif
                                    </div>

                                    <div class="img_wrap">
                                        @if($item->is_mobile_video)
                                            <video controls class="img-fluid">
                                                <source src="{{ asset('storage/'.$item->mobile_image) }}" type="video/{{ pathinfo($item->mobile_image, PATHINFO_EXTENSION) }}">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <img src="{{ asset('storage/'.$item->mobile_image) }}" alt="모바일 이미지" class="img-fluid">
                                        @endif
                                    </div>
                                    <div class="item_txt_wrap">
                                        <p class="title_p">{{$item->title}}</p>
                                        <p class="title_p">{{$item->mobile_title}}</p>
                                        <div class="btn-wrap col-group">
                                            <a href="{{route("admin.bannerEdit", $item->id)}}" class="btn">
                                                수정
                                            </a>
                                            <form action="{{route("admin.bannerDelete", $item->id)}}" method="post"
                                                  onsubmit="return confirmDelete();">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn del-btn">
                                                    삭제
                                                </button>
                                            </form>
                                        </div>
                                        <div class="order_btn_wrap">
                                            {{--                                        <form action="{{route("admin.bannerMove", ['banner' => $item->id, 'direction' => 'up'])}}" method="post">--}}
                                            {{--                                            @csrf--}}
                                            {{--                                            <button type="submit"><i class="xi-arrow-up"></i></button>--}}
                                            {{--                                        </form>--}}
                                            {{--                                        <form action="{{route("admin.bannerMove", ['banner' => $item->id, 'direction' => 'down'])}}" method="post">--}}
                                            {{--                                            @csrf--}}
                                            {{--                                            <button type="submit"><i class="xi-arrow-down"></i></button>--}}
                                            {{--                                        </form>--}}
                                        </div>
                                    </div>
                                </li>
                            </ul>
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

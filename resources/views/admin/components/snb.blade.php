<div class="header-wrap">
    <img src="" alt="" class="header-logo">

    <div class="menu-wrap row-group">
        <div class="gnb">
            <div class="gnb-item">
                <div class="item-default">
                    홈페이지 관리
                    <i class="xi-angle-down-thin"></i>
                </div>
                <div class="sub-gnb row-group">
                    <a href="{{route("admin.popupIndex")}}" class="sub-gnb-item">
                        메인 팝업
                    </a>
                </div>
                <div class="sub-gnb row-group">
                    <a href="{{route("admin.bannerIndex")}}" class="sub-gnb-item">
                        메인 배너
                    </a>
                </div>
                <div class="sub-gnb row-group">
                    <a href="{{route("admin.youtubeIndex")}}" class="sub-gnb-item">
                        메인 유튜브
                    </a>
                </div>
            </div>
            <div class="gnb-item">
                <div class="item-default">
                    고객후기
                    <i class="xi-angle-down-thin"></i>
                </div>
                <div class="sub-gnb row-group">
                    <a href="{{route("admin.announcementIndex")}}" class="sub-gnb-item">
                        공지사항
                    </a>
                </div>
                <div class="sub-gnb row-group">
                    <a href="{{route("admin.questionIndex")}}" class="sub-gnb-item">
                        자주묻는 질문
                    </a>
                </div>
                <div class="sub-gnb row-group">
                    <a href="{{route("admin.reviewIndex")}}" class="sub-gnb-item">
                        고객후기
                    </a>
                </div>
                <div class="sub-gnb row-group">
                    <a href="{{route("admin.inquiryIndex")}}" class="sub-gnb-item">
                        문의하기
                    </a>
                </div>
            </div>
        </div>

        <div class="header-btm">
{{--            <div class="coworkerweb_logo_Wrap">--}}
{{--                <img src="{{ asset('images/coworkerweb_logo.svg') }}" alt="">--}}
{{--            </div>--}}
            <p class="copy-txt">
                Copyright 2023 NOVA Inc. All rights reserved.
            </p>
        </div>
        <form action="" method="post">
            <button type="submit" class="logout-btn">
                <i class="xi-log-out"></i>
                로그아웃
            </button>
        </form>
    </div>
</div>

<script>
    $('.gnb-item').click(function () {
        $(this).toggleClass('active');
    });
</script>

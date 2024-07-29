
$(document).ready(function(){
    //헤더 푸터 컴포넌트
    $('#header').load('./components/header.blade.php');
    $('#footer').load('./components/footer.blade.php');

    $('#pagination').load('/components/pagination.blade.php');
    $('#top_menu').load('/components/top_menu.blade.php');
})

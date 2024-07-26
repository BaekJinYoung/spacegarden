<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            overflow: hidden;
        }
        .background-image {
            background-size: cover;
            background-position: center;
            height: 100vh;
            width: 100vw;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            font-family: Arial, sans-serif;
        }
        @media (min-width: 769px) {
            .background-image {
                background-image: url({{asset('/images/공간정원-공사중이미지.png')}});
            }
        }
        @media (max-width: 768px) {
            .background-image {
                background-image: url({{asset('/images/공간정원-공사중이미지(모바일).png')}});
            }
        }
    </style>
</head>
<body>
<div class="background-image">
</div>
</body>
</html>

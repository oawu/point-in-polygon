<!DOCTYPE html>
<html lang="tw">
    <head>
        <meta http-equiv="Content-Language" content="zh-tw" />
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />

        <title>OA's point in polygon demo!</title>

        <meta name="robots" content="index,follow" />

        <meta name="author" content="吳政賢(OA Wu)" />

        <meta name="keywords" content="point in polygon | OA | Google Maps API | jQuery" />
        <meta name="description" content="就是一個多邊形，拉出範圍，選出範圍內的項目。" />
        <meta property="og:site_name" content="OA's point in polygon demo!" />
        <meta property="og:title" content="OA's point in polygon demo!" />
        <meta property="og:description" content="就是一個多邊形，拉出範圍，選出範圍內的項目。" />
        <meta property="og:url" content="http://polygon.ioa.tw/" />

        <meta property="fb:admins" content="100000100541088" />
        <meta property="fb:app_id" content="640377126095413" />

        <meta property="og:locale" content="zh_TW" />
        <meta property="og:locale:alternate" content="en_US" />

        <meta property="og:type" content="city" />
        <meta property="og:image" content="http://polygon.ioa.tw/assets/img/og.png" alt="OA's point in polygon demo!" />
        <meta property="og:image:type" content="image/jpeg" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />

        <link href="/assets/css/main.css" rel="stylesheet" type="text/css" />
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=zh-TW&libraries=places" language="javascript" type="text/javascript"></script>
        <script src="/assets/js/jquery_v1.10.2/jquery-1.10.2.min.js" language="javascript" type="text/javascript" ></script>
        <script src="/assets/js/main.js" language="javascript" type="text/javascript" ></script>
    </head>
    <body lang="zh-tw">
        <div class='map'>
            <i></i><i></i><i></i><i></i>
            <div id='map'></div>

            <div id='map_menu' class='menu'>
                <div>
                    <div class='add_marker'>新增節點</div>
                </div>
            </div>
            <div id='marker_menu' class='menu'>
                <div>
                    <div class='del'>刪除節點</div>
                </div>
            </div>
            <div id='polyline_menu' class='menu'>
                <div>
                    <div class='add'>插入節點</div>
                </div>
            </div>
        </div>
        <a href="https://github.com/comdan66/point-in-polygon" target="_blank">GitHub</a>
        <a href="http://www.ioa.tw/" target="_blank">作者</a>
        <a href="http://comdan66.github.io/" target="_blank">更多作品</a>
    </body>
</html>

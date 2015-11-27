<!DOCTYPE html>
<html lang="tw">
    <head>
        <meta http-equiv="Content-Language" content="zh-tw" />
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />

        <title>地圖</title>

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
    </body>
</html>

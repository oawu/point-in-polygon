/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

// 定義 Array diff 的 prototype
Array.prototype.diff = function (a) {
    return this.filter (function (i) { return a.map (function (t) { return t.id; }).indexOf (i.id) < 0; });
};
// Ajax error function
window.ajaxError = function (result) {
    console.error (result.responseText);
};

$(function () {
    var $map = $('#map');

    var $menu         = $('.menu');
    var $mapMenu      = $('#map_menu');
    var $markerMenu   = $('#marker_menu');
    var $polylineMenu = $('#polyline_menu');

    var _map      = null;
    var _polyline = null;
    var _points   = [];
    var _markers  = [];


    function circlePath (r) {
        return 'M 0 0 m -' + r + ', 0 '+ 'a ' + r + ',' + r + ' 0 1,0 ' + (r * 2) + ',0 ' + 'a ' + r + ',' + r + ' 0 1,0 -' + (r * 2) + ',0';
    }

    function hideMenu () {
        $menu.css ({ top: -100, left: -100 }).removeClass ('show');
    }
    function loadMarkers () {
        if (_points.length < 3)
            return;

        var bounds = new google.maps.LatLngBounds ();
        _points.forEach (function (t) { bounds.extend (t.position); });

        $.ajax ({
            type: 'POST',
            dataType: 'json',
            url: '/main/ajax',
            data: {
                northEast: {
                    lat: bounds.getNorthEast ().lat (),
                    lng: bounds.getNorthEast ().lng (),
                },
                southWest: {
                    lat: bounds.getSouthWest ().lat (),
                    lng: bounds.getSouthWest ().lng (),
                },
                polygon: _points.map (function (t) {
                    return {
                        lat: t.position.lat (),
                        lng: t.position.lng (),
                    };
                })
            }
        })
        .done (function (result) {
            if (!result.status) return;

            var markers = result.markers.map (function (t) {
                return {
                    id: t.id,
                    marker: new google.maps.Marker ({
                        position: new google.maps.LatLng (t.lat, t.lng),
                        draggable: false,
                    })
                };
            });

            var deletes    = _markers.diff (markers);
            var adds       = markers.diff (_markers);
            var delete_ids = deletes.map (function (t) { return t.id; });
            var add_ids    = adds.map (function (t) { return t.id; });

            deletes.map (function (t) { return t.marker.setMap (null); });
            adds.map (function (t) { return t.marker.setMap (_map); });

            _markers = _markers.filter (function (t) { return $.inArray (t.id, delete_ids) == -1; }).concat (markers.filter (function (t) { return $.inArray (t.id, add_ids) != -1; }));
        })
        .fail (ajaxError);
    }

    function fromLatLngToPoint (latLng, map) {
        var scale = Math.pow (2, map.getZoom ());
        var topRight = map.getProjection ().fromLatLngToPoint (map.getBounds ().getNorthEast ());
        var bottomLeft = map.getProjection ().fromLatLngToPoint (map.getBounds ().getSouthWest ());
        var worldPoint = map.getProjection ().fromLatLngToPoint (latLng);
        return new google.maps.Point ((worldPoint.x - bottomLeft.x) * scale, (worldPoint.y - topRight.y) * scale);
    }

    function setPolyline () {
        if (_points.length > 2) {
            if (!_polyline )
                _polyline = new google.maps.Polygon({
                    map: _map,
                    strokeColor: '#0000FF',
                    strokeOpacity: 0,
                    strokeWeight: 0,
                    fillColor: '#0000FF',
                    fillOpacity: 0.15,
                    draggable: false,
                    geodesic: false
                });

                _polyline.setPath (_points.map (function (t) {
                    return t.position;
                }));
        }

        for (var i = 0; i < _points.length; i++) {
            if (!_points[i].polyline) {
                var polyline = new google.maps.Polyline ({
                    map: _map,
                    strokeColor: 'rgba(68, 77, 145, 1)',
                    strokeWeight: 4,
                    drawPath: function () {
                        var prevPosition = this.prevMarker.getPosition ();
                        var nextPosition = this.nextMarker.getPosition ();
                        this.setPath ([prevPosition, nextPosition]);
                        if (!this.prevMarker.map) this.prevMarker.setMap (_map);
                        if (!this.nextMarker.map) this.nextMarker.setMap (_map);
                        if (!this.map) this.setMap (_map);
                    }
                });

                google.maps.event.addListener (polyline, 'rightclick', function (e) {
                    var point = fromLatLngToPoint (e.latLng, _map);
                    $polylineMenu.css ({ top: point.y, left: point.x })
                                 .data ('lat', e.latLng.lat ())
                                 .data ('lng', e.latLng.lng ())
                                 .addClass ('show').polyline = polyline;
                });
                _points[i].polyline = polyline;
            }

            _points[i].polyline.prevMarker = _points[i - 1] ? _points[i - 1] : _points[_points.length - 1];
            _points[i].polyline.nextMarker = _points[i];
            _points[i].polyline.drawPath ();
        }
        loadMarkers ();
    }

    function initPoints (position, index) {
        var marker = new google.maps.Marker ({
            map: _map,
            draggable: true,
            position: position,
            icon: {
                path: circlePath (8),
                strokeColor: 'rgba(50, 60, 140, 1)',
                strokeWeight: 1,
                fillColor: 'rgba(68, 77, 145, 1)',
                fillOpacity: 0.5
            },
            getPixelPosition: function () {
                var scale = Math.pow (2, this.map.getZoom ());
                var nw = new google.maps.LatLng (
                    this.map.getBounds ().getNorthEast ().lat (),
                    this.map.getBounds ().getSouthWest ().lng ()
                );
                var worldCoordinateNW = this.map.getProjection ().fromLatLngToPoint (nw);
                var worldCoordinate = this.map.getProjection ().fromLatLngToPoint (this.getPosition ());

                return new google.maps.Point (
                    (worldCoordinate.x - worldCoordinateNW.x) * scale,
                    (worldCoordinate.y - worldCoordinateNW.y) * scale
                );
            }
        });

        google.maps.event.addListener (marker, 'drag', setPolyline);

        google.maps.event.addListener (marker, 'rightclick', function (e) {
            var pixel = marker.getPixelPosition ();
            $markerMenu.css ({ top: pixel.y, left: pixel.x }).addClass ('show').marker = marker;
        });
        _points.splice (index ? index : _points.length, 0, marker);

        setPolyline ();
    }

    function initialize () {
        _map = new google.maps.Map ($map.get (0), {
            zoom: 14,
            zoomControl: true,
            scrollwheel: true,
            scaleControl: true,
            mapTypeControl: false,
            navigationControl: true,
            streetViewControl: false,
            disableDoubleClickZoom: true,
            center: new google.maps.LatLng (25.04, 121.55),
        });

        google.maps.event.addListener (_map, 'mousemove', hideMenu);

        google.maps.event.addListener (_map, 'rightclick', function (e) {
            $mapMenu.css ({ top: e.pixel.y, left: e.pixel.x })
                    .data ('lat', e.latLng.lat ())
                    .data ('lng', e.latLng.lng ()).addClass ('show');
        });

        $mapMenu.find ('.add_marker').click (function () {
            initPoints (new google.maps.LatLng ($mapMenu.data ('lat'), $mapMenu.data ('lng')), 0);
            hideMenu ();
        });

        $markerMenu.find ('.del').click (function () {
            _points.splice (_points.indexOf ($markerMenu.marker), 1);
            $markerMenu.marker.setMap (null);
            if ($markerMenu.marker.polyline)
                $markerMenu.marker.polyline.setMap (null);
            setPolyline ();
            hideMenu ();
        });

        $polylineMenu.find ('.add').click (function () {
            if ($polylineMenu.polyline)
                initPoints (new google.maps.LatLng ($polylineMenu.data ('lat'), $polylineMenu.data ('lng')), _points.indexOf ($polylineMenu.polyline.nextMarker));
            hideMenu ();
        });
    }

    google.maps.event.addDomListener (window, 'load', initialize);
});
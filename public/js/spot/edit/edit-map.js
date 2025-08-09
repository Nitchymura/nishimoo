let map;
let marker = null; // ← マーカーを1つだけ保持
let service;

function initMap() {
    const mapElement = document.getElementById("map");

    const lat = parseFloat(mapElement.dataset.lat);
    const lng = parseFloat(mapElement.dataset.lng);

    // DBから位置が取得できていればそれを使い、なければデフォルト（東京）
    const defaultLat = isNaN(lat) ? 35.682839 : lat;
    const defaultLng = isNaN(lng) ? 139.759455 : lng;

    map = new google.maps.Map(mapElement, {
        center: { lat: defaultLat, lng: defaultLng },
        zoom: 15,
    });

    service = new google.maps.places.PlacesService(map);

    // 位置が指定されているならマーカー表示（編集時用）
    if (!isNaN(lat) && !isNaN(lng)) {
        marker = new google.maps.Marker({
            map: map,
            position: { lat: lat, lng: lng },
        });
    }
}

function geocodeAddress() {
    const address = document.getElementById("address").value;
    const geocoder = new google.maps.Geocoder();

    geocoder.geocode({ address: address }, function(results, status) {
        if (status === "OK") {
            const location = results[0].geometry.location;

            map.setCenter(location);

            // 🔥 すでにあるマーカーを削除
            if (marker) {
                marker.setMap(null);
            }

            // 🔥 新しいマーカーをセット
            marker = new google.maps.Marker({
                map: map,
                position: location,
            });

            // 緯度経度と住所をhiddenに反映
            document.getElementById("geo_lat").value = location.lat();
            document.getElementById("geo_lng").value = location.lng();
            document.getElementById("geo_location").value = results[0].formatted_address;
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });
}

// ✅ グローバル登録
window.initMap = initMap;
window.geocodeAddress = geocodeAddress;

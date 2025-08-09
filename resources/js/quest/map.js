window.initMap = function () {
    const locations = window.questMapLocations || [];

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12, // fallback（後で上書きされるので仮でOK）
        center: { lat: 35.6895, lng: 139.6917 }, // fallback center
    });

    const bounds = new google.maps.LatLngBounds();

    locations.forEach(loc => {
        const position = {
            lat: parseFloat(loc.lat),
            lng: parseFloat(loc.lng),
        };

        new google.maps.Marker({
            position,
            map,
            title: loc.title,
        });

        bounds.extend(position);
    });

    if (locations.length > 0) {
        map.fitBounds(bounds);

        // 🔥 fitBoundsのあとにズームを調整（少しだけ遅らせて実行）
        const listener = google.maps.event.addListenerOnce(map, 'bounds_changed', function () {
            const desiredZoom = 12;
            if (map.getZoom() > desiredZoom) {
                map.setZoom(desiredZoom); // ← 遠すぎず近すぎず調整
            }
        });
    }
};


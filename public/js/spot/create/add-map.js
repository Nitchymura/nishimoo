function initMap() {
    const inputLat = parseFloat(document.getElementById("geo_lat").value);
    const inputLng = parseFloat(document.getElementById("geo_lng").value);

    const lat = isNaN(inputLat) ? 35.681236 : inputLat;  // 東京駅
    const lng = isNaN(inputLng) ? 139.767125 : inputLng;

    console.log("初期緯度・経度:", { lat, lng });

    const initialLatLng = { lat, lng };
    const hasLatLng = !isNaN(inputLat) && !isNaN(inputLng);

    const map = new google.maps.Map(document.getElementById("map"), {
        center: initialLatLng,
        zoom: 15,
    });

    let marker = null; // ← 初期ではピンなし

    map.addListener("click", function (event) {
        console.log("地図クリック:", event.latLng.toString());

        if (!marker) {
            marker = new google.maps.Marker({
                position: event.latLng,
                map: map,
                draggable: true,
            });

            marker.addListener("dragend", function (event) {
                const latLng = event.latLng;
                document.getElementById("geo_lat").value = latLng.lat();
                document.getElementById("geo_lng").value = latLng.lng();
                showPlacePhoto(latLng);
            });
        } else {
            marker.setPosition(event.latLng);
        }

        document.getElementById("geo_lat").value = event.latLng.lat();
        document.getElementById("geo_lng").value = event.latLng.lng();
        showPlacePhoto(event.latLng);
    });

    window.map = map;
    window.marker = marker;
}



function showPlacePhoto(latLng) {
    console.log("写真取得対象位置:", latLng.toString());

    const placeRequest = {
        locationBias: { lat: latLng.lat(), lng: latLng.lng() },
        fields: ['photos'],
        query: 'landmark',
    };

    const service = new google.maps.places.PlacesService(document.createElement('div'));

    service.findPlaceFromQuery(placeRequest, function(results, status) {
        console.log("findPlaceFromQuery 結果:", { status, results });

        const photoContainer = document.getElementById("place-photo");
        if (status === google.maps.places.PlacesServiceStatus.OK && results.length > 0) {
            const place = results[0];
            console.log("場所情報:", place);
            if (place.photos && place.photos.length > 0) {
                const photoUrl = place.photos[0].getUrl({ maxWidth: 400, maxHeight: 300 });
                console.log("写真URL:", photoUrl);
                photoContainer.innerHTML = `<img src="${photoUrl}" alt="場所の写真" style="width: 100%; max-width: 400px;">`;
            } else {
                console.log("写真なし");
                photoContainer.innerHTML = `<p>写真が見つかりませんでした。</p>`;
            }
        } else {
            console.warn("写真取得失敗 or 結果なし");
            photoContainer.innerHTML = `<p>写真を取得できませんでした。</p>`;
        }
    });
}

function geocodeAddress() {
    const address = document.getElementById("address").value;
    if (!address) {
        alert("住所を入力してください");
        return;
    }

    const geocoder = new google.maps.Geocoder();

    geocoder.geocode({ address: address }, function(results, status) {
        if (status === "OK" && results[0]) {
            const latLng = results[0].geometry.location;
            console.log("ジオコーディング成功:", latLng.toString());

            const map = window.map;
            let marker = window.marker;

            map.setCenter(latLng);

            if (!marker) {
                marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    draggable: true,
                });

                marker.addListener("dragend", function (event) {
                    const latLng = event.latLng;
                    document.getElementById("geo_lat").value = latLng.lat();
                    document.getElementById("geo_lng").value = latLng.lng();
                    showPlacePhoto(latLng);
                });

                window.marker = marker;
            } else {
                marker.setPosition(latLng);
            }

            document.getElementById("geo_lat").value = latLng.lat();
            document.getElementById("geo_lng").value = latLng.lng();
            showPlacePhoto(latLng);
        } else {
            console.warn("ジオコーディング失敗:", status);
            alert("住所から位置を特定できませんでした。");
        }
    });
}



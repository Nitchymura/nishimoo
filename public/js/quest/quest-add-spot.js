// resources/js/quest/quest-add-spot.js

let modalMap;
let modalGeocoder;
let modalMarker;

// ✅ 初期化関数をグローバルに登録（Google Maps APIのcallback用）
window.initModalMap = function () {
    const defaultLocation = { lat: 35.681236, lng: 139.767125 }; // 東京駅

    modalGeocoder = new google.maps.Geocoder();

    const mapElement = document.getElementById("modal-map");
    if (!mapElement) {
        console.warn("⚠️ modal-map 要素が見つかりません");
        return;
    }

    modalMap = new google.maps.Map(mapElement, {
        zoom: 14,
        center: defaultLocation,
    });

    modalMarker = new google.maps.Marker({
        position: defaultLocation,
        map: modalMap,
    });

    const latInput = document.getElementById("modal-geo-lat");
    const lngInput = document.getElementById("modal-geo-lng");
    if (latInput && lngInput) {
        latInput.value = defaultLocation.lat;
        lngInput.value = defaultLocation.lng;
    }
};

// 📍 検索からピンを再配置する関数
window.modalGeocodeAddress = function () {
    const address = document.getElementById("modal-address").value;
    console.log("🔍 Searching for:", address);

    if (!modalGeocoder || !modalMap) {
        console.error("⚠️ 地図が初期化されていません");
        return;
    }

    modalGeocoder.geocode({ address }, function (results, status) {
        if (status === "OK") {
            const location = results[0].geometry.location;
            modalMap.setCenter(location);

            modalMarker.setMap(null);
            modalMarker = new google.maps.Marker({
                map: modalMap,
                position: location,
            });

            const latInput = document.getElementById("modal-geo-lat");
            const lngInput = document.getElementById("modal-geo-lng");
            if (latInput && lngInput) {
                latInput.value = location.lat();
                lngInput.value = location.lng();
            }

            console.log("📌 緯度経度設定:", location.lat(), location.lng());
        } else {
            console.error("❌ ジオコーディングに失敗:", status);
            alert("Address not found. Try again.");
        }
    });
};

// モーダル表示時に地図を初期化

document.addEventListener("DOMContentLoaded", () => {
    const modalId = `addSpotModal-${window.questId}`;
    const modalEl = document.getElementById(modalId);

    if (!modalEl) {
        console.warn(`🔍 Modal element not found: #${modalId}`);
        return;
    }

    modalEl.addEventListener("shown.bs.modal", function () {
        setTimeout(() => {
            if (window.initModalMap) window.initModalMap();
        }, 300);
    });

    const spotForm = document.getElementById("modal-spot-create-form");
    if (!spotForm) {
        console.warn("❗ Spot作成フォームが見つかりません");
        return;
    }

    spotForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        // ✅ title → name にしておく（コントローラーは name しか見てない）
        formData.set('name', formData.get('title'));

        try {
            const res = await fetch("/spot/store", {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").content,
                },
                body: formData,
            });

            if (!res.ok) throw await res.json();

            const data = await res.json();

            document.getElementById("spot_name").value = data.title;
            document.getElementById("spot_business_id").value = data.id;
            document.getElementById("spot_business_type").value = "spot";

            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

        } catch (err) {
            console.error("❌ Spot作成エラー", err);
        }
    });
});

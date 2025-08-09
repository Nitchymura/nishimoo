function initMap() {
    const mapElement = document.getElementById("map");

    const lat = parseFloat(mapElement.dataset.lat);
    const lng = parseFloat(mapElement.dataset.lng);
    const defaultLat = isNaN(lat) ? 35.682839 : lat;
    const defaultLng = isNaN(lng) ? 139.759455 : lng;

    const map = new google.maps.Map(mapElement, {
        center: { lat: defaultLat, lng: defaultLng },
        zoom: 15,
    });

    const service = new google.maps.places.PlacesService(document.createElement('div'));
    const request = {
        locationBias: { lat: defaultLat, lng: defaultLng },
        fields: ['photos'],
        query: 'landmark',

    };
    const marker = new google.maps.Marker({
        position: { lat: defaultLat, lng: defaultLng },
        map: map,
        title: "Spot Location"
      });
      

    const photoContainer = document.getElementById("spot-photo-container");
    const existingImages = photoContainer.querySelectorAll("img");

    if (existingImages.length === 0) {
        service.findPlaceFromQuery(request, function(results, status) {
            const photoContainer = document.getElementById("spot-photo-container");
    
            if (status === google.maps.places.PlacesServiceStatus.OK && results.length > 0) {
                const place = results[0];
                if (place.photos && place.photos.length > 0) {
                    document.getElementById("no-photos-message")?.remove(); // 最初の"no photos"削除
                    const maxImages = 3;
                    const limitedPhotos = place.photos.slice(0, maxImages);
                    limitedPhotos.forEach(photo => {
                        const photoUrl = photo.getUrl({ maxWidth: 400, maxHeight: 300 });
                        const col = document.createElement("div");
                        col.className = "col-6 col-sm-4 col-md-3 mb-4";
                        col.innerHTML = `
                            <div class="w-100">
                                <img src="${photoUrl}" alt="Place Photo" class="w-100 rounded-3">
                            </div>`;
                        photoContainer.appendChild(col);
                    });
                }
            } else {
                // "No photos." はそのままで、エラーだけ追加
                const errorMsg = document.createElement("p");
                errorMsg.className = "text-center w-100";
                errorMsg.textContent = "Unable to fetch photos from Google.";
                photoContainer.appendChild(errorMsg);
            }
        });
    }
    
}

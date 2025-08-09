function adjustDescriptionHeight() {
    let spotImageContainers = document.querySelectorAll(".spot-image-container");
    let descriptions = document.querySelectorAll(".spot-description");

    if (spotImageContainers.length === 0 || descriptions.length === 0) {
        console.warn("⚠️ adjustDescriptionHeight: 必要な要素が見つかりません");
        return;
    }

    console.log("🎯 adjustDescriptionHeight: 実行開始！");

    spotImageContainers.forEach((container, index) => {
        let images = container.querySelectorAll(".spot-image");
        let description = descriptions[index];

        if (!description) return;

        // 画像の合計高さを計算
        let totalImageHeight = 0;
        images.forEach(image => {
            totalImageHeight += image.clientHeight;
        });

        let descriptionHeight = description.scrollHeight;

        console.log(`📏 [Spot ${index + 1}] 画像の合計高さ:`, totalImageHeight, " 説明文の高さ:", descriptionHeight);

        if (descriptionHeight > totalImageHeight) {
            console.log("🟢 説明文が長いので高さ制限");
            description.style.maxHeight = totalImageHeight + "px";
            description.style.overflowY = "auto";
        } else {
            console.log("🔵 説明文が短いので制限なし");
            description.style.maxHeight = "none";
            description.style.overflowY = "hidden";
        }
    });
}

// ウィンドウのリサイズ時にも適用
window.addEventListener("load", adjustDescriptionHeight);
window.addEventListener("resize", adjustDescriptionHeight);

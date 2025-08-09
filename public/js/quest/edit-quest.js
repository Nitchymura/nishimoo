// Js/quest/edit-quest.js

    // **アップロードした画像を保存するリスト**
    let uploadedImagesList = [];

    document.addEventListener("DOMContentLoaded", function () {

        const pathParts = window.location.pathname.split('/');
        const questId = pathParts.find(part => /^\d+$/.test(part));
        console.log("🎯 questId:", questId); // 期待通り 31 とかになる
    
        // 📌 HTML要素を取得
        const fileInput = document.getElementById("image");
        const uploadBtn = document.getElementById("upload-btn");
        const uploadedFileNames = document.getElementById("uploaded-file-names");
    
        if (!fileInput || !uploadBtn || !uploadedFileNames) {
            console.error("📌 フォームの要素が見つかりません。");
            return;
        }
    
        // **「追加」ボタンを無効化**
        uploadBtn.disabled = true;
    
        // **ファイルが選択されたときの処理**
        fileInput.addEventListener("change", function () {
            if (fileInput.files.length === 0) {
                return;
            }
    
            // **選択されたファイルを `uploadedImagesList` に追加**
            Array.from(fileInput.files).forEach(file => {
                uploadedImagesList.push(file);
            });
    
            updateUploadedFileNames(); // **ファイルリストを更新**
            
            console.log("🖼 アップロード画像リスト:", uploadedImagesList); // 🔥 デバッグ用
    
            // **ファイルインプットをクリア**
            fileInput.value = "";
            uploadBtn.disabled = true;
        });
    
        // **画像リストを更新してサムネイルを表示**
        function updateUploadedFileNames() {
            uploadedFileNames.innerHTML = ""; // 一旦クリアして再表示
        
            uploadedImagesList.forEach((file, index) => {
                const fileItem = document.createElement("div");
                fileItem.classList.add("col-auto", "text-center", "me-2", "position-relative");
        
                // 🖼 サムネイル画像
                const reader = new FileReader();
                reader.onload = function (e) {
                    const thumbnail = document.createElement("img");
                    thumbnail.classList.add("img-thumbnail");
                    thumbnail.src = e.target.result;
                    thumbnail.alt = `Uploaded Image ${index + 1}`;
                    thumbnail.style.width = "150px"; // サイズ統一
                    thumbnail.style.height = "auto";
        
                    fileItem.appendChild(thumbnail);
                };
                reader.readAsDataURL(file);
        
                // ❌ 削除ボタン
                const deleteButton = document.createElement("button");
                deleteButton.classList.add(
                    "btn", "btn-sm", "btn-red",
                    "position-absolute", "bottom-0", "end-0",
                    "m-1", "text-white"
                );
                deleteButton.innerHTML = "<i class='fa-solid fa-trash'></i>";
                deleteButton.addEventListener("click", function () {
                    removeImage(index);
                });
        
                fileItem.appendChild(deleteButton);
                uploadedFileNames.appendChild(fileItem);
            });
        }
        
        // **画像を削除する関数**
        function removeImage(index) {
            uploadedImagesList.splice(index, 1);
            updateUploadedFileNames(); // リストを更新
        }
    
        console.log("✅ スクリプトが適用されました！");

        // ぽわん表示
            const sections = document.querySelectorAll(".reveal-section");
    
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("revealed");
                        observer.unobserve(entry.target); // 一度表示されたら監視解除
                    }
                });
            }, {
                threshold: 0.08, // 要素が10%見えたら発火
            });
    
            sections.forEach(section => {
                observer.observe(section);
            });
        });
    
    




//=============================FORM2=============================================================d
// ====================search=================
window.addEventListener("DOMContentLoaded", function () {
    const roleId = window.authRoleId; 

    if (roleId === 'guest') {
        console.log("👤 ゲストユーザー、サーチ処理をスキップ");
        return;
    }

    const numericRoleId = parseInt(roleId, 10);

    const searchInput = document.getElementById("spot_name");
    const searchResults = document.getElementById("searchResults");

    // console.log("✅ spot_name:", searchInput);
    // console.log("✅ searchResults:", searchResults);
    // console.log(numericRoleId);

    if (numericRoleId === 2)  {
        // role_id 2（企業ユーザー）の場合はクリック時に自分のビジネスを表示
        let isBusinessSelected = false;
        searchInput.addEventListener("focus", function () {
            const roleId = parseInt(document.body.dataset.roleId);

            if (roleId === 2 && isBusinessSelected) {
                alert("ビジネスは1つまで選択可能です。変更できません。");
                searchInput.blur();
            }
            fetch("/questbody/user/searchbusinesses")
            .then(response => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.json();
              })
                .then(data => {
                    console.log("🟢 成功:", data);
                    searchResults.innerHTML = "";
                    if (data.length > 0) {
                        const resultList = document.createElement("ul");
                        resultList.classList.add("list-group");

                        data.forEach(item => {
                            const listItem = document.createElement("li");
                            listItem.classList.add("list-group-item");
                            listItem.textContent = item.name;
                            listItem.dataset.type = item.type;
                            listItem.dataset.id = item.id;

                            listItem.addEventListener("click", function () {
                                searchInput.value = item.name;
                                document.getElementById("spot_business_type").value = item.type;
                                document.getElementById("spot_business_id").value = item.id;
                                searchResults.innerHTML = "";
                                isBusinessSelected = true; // ここでロック
                            });

                            resultList.appendChild(listItem);
                        });

                        searchResults.appendChild(resultList);
                    } else {
                        searchResults.innerHTML = "<p class='text-muted'>No businesses found</p>";
                    }
                })
                .catch(error => console.error("エラー:", error));
        });
    } else {
        // 🔍 role_id 1：入力に応じた検索
        searchInput.addEventListener("input", function () {
            console.log("input イベント発火"); 
            const query = this.value.trim();
            if (query.length < 2) {
                searchResults.innerHTML = "";
                return;
            }

            fetch(`/questbody/search/Ajax?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = "";
                    if (data.length > 0) {
                        const resultList = document.createElement("ul");
                        resultList.classList.add("list-group");

                        data.forEach(item => {
                            const displayText = item.title ?? item.name;

                            const listItem = document.createElement("li");
                            listItem.classList.add("list-group-item");
                            listItem.textContent = displayText;
                            listItem.dataset.type = item.type;
                            listItem.dataset.id = item.id;

                            listItem.addEventListener("click", function () {
                                searchInput.value = displayText;
                                document.getElementById("spot_business_type").value = item.type;
                                document.getElementById("spot_business_id").value = item.id;
                                searchResults.innerHTML = "";
                            });

                            resultList.appendChild(listItem);
                        });

                        searchResults.appendChild(resultList);
                    } else {
                        searchResults.innerHTML = "<p class='text-muted'>No results found</p>";
                    }
                })
                .catch(error => console.error("検索エラー:", error));
        });
    }
});
// console.log(document.getElementById("addbodybtn"));

// ===================================================-ADD QUESTBODY======================
document.getElementById("addbodybtn").addEventListener("click", async function(event) {
    event.preventDefault();
    console.log("🛠 ADD SPOT ボタンが押されました");

    //Error check
    const roleId = parseInt(window.authRoleId);
    const spotInput = document.getElementById("spot_name");
    const introInput = document.getElementById("introduction");
    const businessTitleInput = document.getElementById("business_title");
    const spotError = document.getElementById("spot-error");
    const introError = document.getElementById("intro-error");
    const imageError = document.getElementById("image-error");
    const businessTitleError = document.getElementById("business-title-error");

    let hasError = false;

    // スポットが空
    if (!spotInput.value.trim()) {
        if (spotError) spotError.classList.remove("d-none");
        hasError = true;
    } else {
        if (spotError) spotError.classList.add("d-none");
    }

    // 説明が空
    if (!introInput.value.trim()) {
        if (introError) introError.classList.remove("d-none");
        hasError = true;
    } else {
        if (introError) introError.classList.add("d-none");
    }

    // role_id = 2 のときだけ business_title をチェック
    if (roleId === 2) {
        if (!businessTitleInput || !businessTitleInput.value.trim()) {
            if (businessTitleError) businessTitleError.classList.remove("d-none");
            hasError = true;
        } else {
            if (businessTitleError) businessTitleError.classList.add("d-none");
        }
    }

    // 画像がゼロ
    if (uploadedImagesList.length === 0) {
        if (imageError) imageError.classList.remove("d-none");
        hasError = true;
    } else {
        if (imageError) imageError.classList.add("d-none");
    }

    if (hasError) return; // ❌ エラーがあるときは送信ストップ



    // 入力値の取得
    const day = parseInt(document.getElementById("day_number").value, 10) || 1;
    const spot = document.getElementById("spot_name").value;
    const description = document.getElementById("introduction").value;
    const fileInput2 = uploadedImagesList;
    // const isAgendaChecked = document.getElementById("agenda").checked;

    if (!spot || !description) {
        alert("スポットと説明を入力してください");
        return;
    }

    if (uploadedImagesList.length === 0) {
        alert("画像を1枚以上アップロードしてください");
        return;
    }

    let imageSrcList = [];  // 画像の配列
    console.log(imageSrcList);
    console.log(fileInput2);

    imageSrcList = fileInput2;
    console.log("画像が処理された！");

    //redirectせずにformを送信
    // `fetch` で送信する処理
        let form2 = document.getElementById("body_form");
        let formData2 = new FormData(form2);

        console.log("🔥 form2:", form2);
        if (!form2) {
            console.error("❌ エラー: form2 が見つかりません！");
            return;
        }
        // ✅ `uploadedImagesList` を `FormData2` に追加する処理
        uploadedImagesList.forEach((file, index) => {
            if (file instanceof File) {  // 🔥 `File` オブジェクトか確認
                formData2.append("images[]", file); // `images[]` 配列として追加
                console.log(`✅ 画像 ${index + 1} を FormData2 に追加:`, file.name);
            } else {
                console.error(`🛑 画像 ${index + 1} は File オブジェクトではありません！`, file);
            }
        });

    // ✅ `FormData2` の中身を確認
    for (let pair of formData2.entries()) {
        console.log(`🔥 ${pair[0]}:`, pair[1]);
    }

        console.log("🔥 formData2 を取得:", formData2); 
        // 🚀 fetch で送信
        let response = await fetch("/questbody/store", {
            method: "POST",
            body: formData2,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
              },
        });
        if (response.ok) {
            const result = await response.json(); // ← JSONで受け取る
            console.log("✅ 保存成功！", result);
        
            if (result.quest_id) {
                window.location.href = `/quest/${result.quest_id}/edit`;
            } else {
                alert("保存は成功しましたが、quest_id が返されませんでした。");
            }
        } else {
            const errorText = await response.text();
            console.error("❌ 保存失敗:", errorText);
            alert("保存に失敗しました");
        }

        setTimeout(() => console.log('🔥 動いてる！'), 1000);
            clearForm2(); // 入力をクリア
            refreshQuestBody();
        // });
    });
    
    function clearForm2() {
        console.log("🧹 フォーム2をクリアします！");
    
        const spotNameInput = document.getElementById("spot_name");
        const introInput = document.getElementById("introduction");
        const imageInput = document.getElementById("image");
        const uploadedFileNames = document.getElementById("uploaded-file-names");
    
        if (spotNameInput) spotNameInput.value = "";
        if (introInput) introInput.value = "";
        if (imageInput) imageInput.value = "";
        if (uploadedFileNames) uploadedFileNames.innerHTML = "";
    
        // 💡 role_id 2（企業ユーザー）のときだけ business_title をクリア
        if (parseInt(window.authRoleId) === 2) {
            const businessTitleInput = document.getElementById("business_title");
            if (businessTitleInput) businessTitleInput.value = "";
        }
    
        // 画像リストも初期化
        uploadedImagesList = [];
    }
    
    
// });
// ===================load==========================================================

async function refreshQuestBody() {
    const questId = getQuestIdFromURL();
    const response = await fetch(`/questbody/getAllQuestBodies/${questId}`);
    
    if (response.ok) {
        const questBodiesHTML = await response.text();
        document.getElementById("quest-body-container").innerHTML = questBodiesHTML;

        const images = document.querySelectorAll("#quest-body-container img");

        if (images.length > 0) {
            let loadedCount = 0;

            images.forEach(img => {
                img.onload = () => {
                    loadedCount++;
                    if (loadedCount === images.length) {
                        console.log("✅ すべての画像読み込み完了！");
                        adjustDescriptionHeight(); // ✅ 高さ調整
                    }
                };
            });

            // 🔥万が一 onload が走らなかったときの保険
            setTimeout(() => {
                console.warn("⏰ onload 全部待たずに強制実行！");
                adjustDescriptionHeight();
            }, 1000);
        } else {
            console.log("⚠️ 表示すべき画像なし");
            adjustDescriptionHeight();
        }
    }
}


// function getQuestIdFromURL() {
//     const path = window.location.pathname;
//     const pathParts = path.split('/');
//     return pathParts[pathParts.length - 1];
// }

//======================================================ADJUST HEIGHT
function adjustDescriptionHeight() {
    console.log("🎯 adjustDescriptionHeight: 実行開始！");

    let spotEntries = document.querySelectorAll(".spot-entry"); // 各スポットを取得

    if (spotEntries.length === 0) {
        console.warn("⚠️ adjustDescriptionHeight: スポットが見つかりません");
        return;
    }

    spotEntries.forEach((spot, index) => {
        let imageContainer = spot.querySelector(".image-container");
        let description = spot.querySelector(".spot-description");

        if (!imageContainer || !description) {
            console.warn(`⚠️ Spot ${index + 1}: 画像コンテナまたは説明が見つかりません`);
            return;
        }

        // 画像の高さが確定するまで待つ（非同期に調整）
        let totalImageHeight = 0;
        let images = imageContainer.querySelectorAll("img");

        if (images.length === 0) {
            console.warn(`⚠️ Spot ${index + 1}: 画像がありません`);
            return;
        }

        images.forEach(image => {
            image.onload = () => {
                totalImageHeight += image.clientHeight;
                console.log(`📷 画像の高さ: ${image.clientHeight}px, 合計: ${totalImageHeight}px`);
                applyHeightAdjustment(description, totalImageHeight);
            };
        });

        // すでに読み込まれている画像の高さも考慮
        setTimeout(() => {
            images.forEach(image => {
                totalImageHeight += image.clientHeight;
            });
            console.log(`📏 [Spot ${index + 1}] 画像の合計高さ: ${totalImageHeight}px, 説明の高さ: ${description.scrollHeight}px`);
            applyHeightAdjustment(description, totalImageHeight);
        }, 500);
    });

    

    
}

// 🔥 高さ調整の適用ロジックを関数化
function applyHeightAdjustment(description, totalImageHeight) {
    if (!description) return;

    let descriptionHeight = description.scrollHeight;

    if (descriptionHeight > totalImageHeight) {
        console.log("🟢 説明文が長いので高さ制限を適用");
        description.style.maxHeight = totalImageHeight + "px";
        description.style.overflowY = "auto";
    } else {
        console.log("🔵 説明文が短いので制限なし");
        description.style.maxHeight = "none";
        description.style.overflowY = "hidden";
    }
}

// 🔥 ページの読み込み時とリサイズ時に実行
// window.addEventListener("load", adjustDescriptionHeight);
window.addEventListener("resize", adjustDescriptionHeight);




//=====================================================Modal================================

    window.addEventListener("load", adjustDescriptionHeight);
    window.addEventListener("resize", adjustDescriptionHeight);
//=================================================AGENDA
    document.querySelectorAll('.agenda-toggle').forEach(toggle => {
        toggle.addEventListener('change', async function () {
            const questbodyId = this.dataset.id;
            const isAgenda = this.checked ? 1 : 0;
    
            try {
                const response = await fetch(`/questbody/agenda/${questbodyId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ is_agenda: isAgenda })
                });
    
                const result = await response.json();
                if (response.ok) {
                    console.log(`🟢 Agenda status updated for ID ${questbodyId}:`, result.is_agenda);
                } else {
                    console.warn(`⚠️ 更新失敗:`, result);
                    alert("Agendaの更新に失敗しました");
                }
            } catch (error) {
                console.error("❌ 通信エラー:", error);
                alert("通信エラーが発生しました");
            }
        });
    });
    



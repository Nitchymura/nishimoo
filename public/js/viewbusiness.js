document.addEventListener('DOMContentLoaded', function() {
    // プロモーションカルーセルの設定
    initPromotionCarousel();
    
    // 星評価機能の初期化
    initStarRating();
    
    // 既存のレビュー星表示の初期化
    initExistingRatingStars();
    
    // フォロー/アンフォロー機能の初期化
    initFollowButtons();
});

/**
 * フォロー/アンフォロー機能の初期化
 */
function initFollowButtons() {
    const followContainer = document.querySelector('.follow-container');
    if (!followContainer) return;
    
    const userId = followContainer.getAttribute('data-user-id');
    const followButton = followContainer.querySelector('.follow-button');
    const unfollowButton = followContainer.querySelector('.unfollow-button');
    
    // フォローボタンのクリックイベント
    if (followButton) {
        followButton.addEventListener('click', function() {
            // フォローAPIを叩く
            followUser(userId).then(success => {
                if (success) {
                    // ボタンの表示を切り替え
                    followButton.classList.add('d-none');
                    unfollowButton.classList.remove('d-none');
                }
            });
        });
    }
    
    // アンフォローボタンのクリックイベント
    if (unfollowButton) {
        unfollowButton.addEventListener('click', function() {
            // アンフォローAPIを叩く
            unfollowUser(userId).then(success => {
                if (success) {
                    // ボタンの表示を切り替え
                    unfollowButton.classList.add('d-none');
                    followButton.classList.remove('d-none');
                }
            });
        });
    }
}

/**
 * ユーザーをフォローする
 * @param {number} userId - フォローするユーザーのID
 * @returns {Promise<boolean>} - 成功したかどうか
 */
async function followUser(userId) {
    try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/follow/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            return true;
        }
        return false;
    } catch (error) {
        console.error('Follow error:', error);
        return false;
    }
}

/**
 * ユーザーのフォローを解除する
 * @param {number} userId - フォロー解除するユーザーのID
 * @returns {Promise<boolean>} - 成功したかどうか
 */
async function unfollowUser(userId) {
    try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch(`/follow/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            return true;
        }
        return false;
    } catch (error) {
        console.error('Unfollow error:', error);
        return false;
    }
}

/**
 * 既存のレビュー星表示の初期化
 */
function initExistingRatingStars() {
    const commentStars = document.querySelectorAll('.comment-stars');
    
    commentStars.forEach(container => {
        const rating = parseInt(container.getAttribute('data-rating')) || 0;
        const stars = container.querySelectorAll('i.fa-star');
        
        // 評価に基づいて星を塗りつぶす
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('fa-regular');
                star.classList.add('fa-solid');
            }
        });
    });
}

/**
 * 星評価機能の初期化
 */
function initStarRating() {
    const starContainer = document.querySelector('.star-rating');
    if (!starContainer) return;
    
    const stars = starContainer.querySelectorAll('.star-rating-item');
    const ratingInput = document.getElementById('rating-value');
    
    // 初期値がある場合は星を塗りつぶす
    const initialRating = parseInt(ratingInput.value) || 0;
    if (initialRating > 0) {
        updateStars(initialRating);
    }
    
    // 星をクリックしたときの処理
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            ratingInput.value = rating;
            updateStars(rating);
        });
        
        // マウスオーバー時のプレビュー
        star.addEventListener('mouseenter', function() {
            const hoverRating = parseInt(this.getAttribute('data-rating'));
            previewStars(hoverRating);
        });
    });
    
    // マウスが星評価エリアから離れたとき、実際の評価値に戻す
    starContainer.addEventListener('mouseleave', function() {
        const currentRating = parseInt(ratingInput.value) || 0;
        updateStars(currentRating);
    });
    
    // 星を指定の数まで塗りつぶす（実際の評価値の設定）
    function updateStars(rating) {
        stars.forEach(star => {
            const starRating = parseInt(star.getAttribute('data-rating'));
            if (starRating <= rating) {
                star.classList.remove('fa-regular');
                star.classList.add('fa-solid');
            } else {
                star.classList.remove('fa-solid');
                star.classList.add('fa-regular');
            }
        });
    }
    
    // マウスオーバーでのプレビュー表示
    function previewStars(rating) {
        stars.forEach(star => {
            const starRating = parseInt(star.getAttribute('data-rating'));
            if (starRating <= rating) {
                star.classList.remove('fa-regular');
                star.classList.add('fa-solid');
            } else {
                star.classList.remove('fa-solid');
                star.classList.add('fa-regular');
            }
        });
    }
}

/**
 * プロモーションカルーセルの初期化
 */
function initPromotionCarousel() {
    const carousel = document.querySelector('.promotion-carousel');

    if (!carousel) {
        console.error('Carousel element not found');
        return;
    }

    const itemsContainer = carousel.querySelector('.carousel-items-container');
    if (!itemsContainer) {
        console.error('Items container not found');
        return;
    }

    const items = carousel.querySelectorAll('.promotion-item');
    const totalItems = items.length;

    console.log('Total promotion items:', totalItems);

    if (totalItems <= 3) {
        // 3つ以下の場合は全て表示
        items.forEach(item => {
            item.classList.add('active'); // displayではなくclassで制御
        });
        // ナビゲーションを非表示
        const controls = carousel.querySelector('.carousel-controls');
        if (controls) controls.style.display = 'none';
        const indicators = carousel.querySelector('.carousel-indicators');
        if (indicators) indicators.style.display = 'none';
        return;
    }

    let currentIndex = 0;
    const itemsPerSlide = 3; // 1スライドに3つのアイテムを表示
    const maxIndex = Math.max(0, totalItems - itemsPerSlide);

    // クエリセレクタを使用してボタンを取得
    const prevBtn = carousel.querySelector('.carousel-arrow.prev');
    const nextBtn = carousel.querySelector('.carousel-arrow.next');

    console.log('Prev button:', prevBtn);
    console.log('Next button:', nextBtn);

    const indicators = carousel.querySelectorAll('.carousel-indicator');

    if (!prevBtn || !nextBtn) {
        console.error('Navigation buttons not found');
        return;
    }

    // カルーセルの表示を更新する関数
    function updateCarousel() {
        console.log('Updating carousel, current index:', currentIndex);

        items.forEach((item, index) => {
            // 現在のインデックスから3つまでを表示
            const isVisible = index >= currentIndex && index < currentIndex + itemsPerSlide;
            if (isVisible) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });

        // 次へボタンの無効化（オプション）
        nextBtn.disabled = currentIndex >= maxIndex;
        nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';

        // 前へボタンの無効化（オプション）
        prevBtn.disabled = currentIndex <= 0;
        prevBtn.style.opacity = currentIndex <= 0 ? '0.5' : '1';

        // インジケーターの更新
        if (indicators.length > 0) {
            const indicatorIndex = Math.floor(currentIndex / itemsPerSlide);
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === indicatorIndex);
            });
        }
    }

    // 初期表示
    updateCarousel();

    // 次へボタンのクリックイベント - 直接関数を定義
    function handleNextClick(e) {
        if (e) e.preventDefault();
        console.log('Next button clicked');

        if (currentIndex < maxIndex) {
            currentIndex = Math.min(currentIndex + itemsPerSlide, maxIndex);
        } else {
            currentIndex = 0; // 最初に戻る
        }

        updateCarousel();
    }

    // 前へボタンのクリックイベント - 直接関数を定義
    function handlePrevClick(e) {
        if (e) e.preventDefault();
        console.log('Previous button clicked');

        if (currentIndex > 0) {
            currentIndex = Math.max(currentIndex - itemsPerSlide, 0);
        } else {
            // 最後のグループに移動
            currentIndex = maxIndex;
        }

        updateCarousel();
    }

    // イベントリスナーの設定
    nextBtn.addEventListener('click', handleNextClick);
    prevBtn.addEventListener('click', handlePrevClick);

    // 右矢印キーと左矢印キーでもナビゲーション可能に
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowRight') {
            handleNextClick();
        } else if (e.key === 'ArrowLeft') {
            handlePrevClick();
        }
    });

    // インジケーターのクリックイベント
    if (indicators.length > 0) {
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', function() {
                console.log('Indicator clicked:', index);

                currentIndex = Math.min(index * itemsPerSlide, maxIndex);
                updateCarousel();
            });
        });
    }

    // 自動スライド（オプション）
    let autoSlide = setInterval(handleNextClick, 5000); // 5秒ごとに切り替え

    // マウスオーバーで自動スライドを停止
    carousel.addEventListener('mouseenter', function() {
        clearInterval(autoSlide);
    });

    // マウスアウトで自動スライドを再開
    carousel.addEventListener('mouseleave', function() {
        clearInterval(autoSlide); // 既存のタイマーをクリア
        autoSlide = setInterval(handleNextClick, 5000);
    });
} 
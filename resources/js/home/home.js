function initSlick(slider) {
    if (!$(slider).hasClass('slick-initialized')) {
        $(slider).slick({
            autoplay: true,
            dots: true,
            slidesToShow: 5,
            slidesToScroll: 1
        });
    }
}

function animationOthers(defaultTab = 'tab-fol') {
    document.querySelector('.tag-title')?.classList.add('active');
    document.querySelector('.tag-category')?.classList.add('active');
    document.querySelector('.all-posts')?.classList.add('active');
    document.querySelector('.all-posts-img')?.classList.add('active');

    const slider = document.querySelector(`#${defaultTab} .slider`);
    if (slider) {
        slider.classList.add('active');
        initSlick(slider);
    }
}

window.addEventListener('DOMContentLoaded', () => {
    const defaultTab = 'tab-fol';

    // 初期タブ切り替え処理
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.getElementById(defaultTab)?.classList.add('active');

    const defaultLink = document.querySelector(`.tab-button[href="#${defaultTab}"]`);
    if (defaultLink) {
        defaultLink.classList.add('active');
        const defaultImg = defaultLink.querySelector('img');
        if (defaultImg && defaultImg.dataset.active) {
            defaultImg.src = defaultImg.dataset.active;
        }
    }

    // ✅ .line のアニメが完了した後に表示開始（←ここが同期のカギ）
    const target = document.querySelector('.line');
    if (target) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');

                    entry.target.addEventListener('transitionend', () => {
                        animationOthers(defaultTab);
                    }, { once: true });

                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 1
        });

        observer.observe(target);
    }

    // ✅ タブクリック時の切り替え
    document.querySelectorAll('.tab-button').forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();

            history.pushState("", document.title, window.location.pathname + window.location.search);

            const targetSelector = tab.dataset.category;
            const targetContent = document.querySelector(targetSelector);

            // タブ切り替え
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            if (targetContent) targetContent.classList.add('active');

            // sliderの切り替え
            document.querySelectorAll('.slider').forEach(sl => sl.classList.remove('active'));
            const slider = targetContent?.querySelector('.slider');
            if (slider) {
                slider.classList.add('active');
                setTimeout(() => {
                    initSlick(slider);
                }, 300);
            }

            // タブ画像切り替え
            document.querySelectorAll('.tab-button').forEach(link => {
                link.classList.remove('active');
                const img = link.querySelector('img');
                if (img && img.dataset.default) {
                    img.src = img.dataset.default;
                }
            });

            tab.classList.add('active');
            const activeImg = tab.querySelector('img');
            if (activeImg && activeImg.dataset.active) {
                activeImg.src = activeImg.dataset.active;
            }
        });
    });
});


    document.addEventListener('DOMContentLoaded', function(){
        const target    = document.querySelector('.faq-body');
        const question  = document.querySelectorAll('.first-question');
        const observer  = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if(entry.isIntersecting){
                    entry.target.classList.add('active');

                    target.addEventListener("animationend", () =>{
                        question.forEach(q => q.classList.add('active'));
                    }, {once: true});

                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.7
        });

        if(target){
            observer.observe(target);
        }
    });

<div id="scroll-controls" class="fixed bottom-32 right-4 md:bottom-8 md:right-8 flex flex-col space-y-3 z-50">
        <button id="scroll-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                class="cursor-pointer p-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border border-gray-200 dark:border-gray-600 rounded-full shadow-lg text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-gray-700 hover:border-blue-200 dark:hover:border-blue-500 transition-all duration-300 transform translate-y-20 opacity-0 invisible"
                title="{{ __('Scroll to Top') }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
            </svg>
        </button>
        <button id="scroll-to-bottom" onclick="window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'})" 
                class="cursor-pointer p-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border border-gray-200 dark:border-gray-600 rounded-full shadow-lg text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-gray-700 hover:border-blue-200 dark:hover:border-blue-500 transition-all duration-300"
                title="{{ __('Scroll to Bottom') }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>

<script>
    const scrollTopBtn = document.getElementById('scroll-to-top');
    const scrollBottomBtn = document.getElementById('scroll-to-bottom');
    
    // Durumları takip edeceğimiz değişkenler
    let isScrolled = false;
    let isAtBottom = false;
    let isAdVisible = false;

    // Her bir reklamın ekrandaki görünürlük durumunu hafızada tutacak bir Map oluşturuyoruz
    const activeAds = new Map();

    // Butonların görünürlüğünü tek yerden yöneten fonksiyon
    function updateButtonsVisibility() {
        if (!scrollTopBtn || !scrollBottomBtn) return;

        // Ekranın mobil olup olmadığını kontrol ediyoruz (Tailwind 'md' breakpoint'i altı: 768px)
        const isMobile = window.innerWidth < 980;

        // Eğer mobildeysek reklamın görünürlüğünü dikkate alıyoruz, masaüstündeysek (normal genişlikte) reklamı es geçiyoruz
        const hideBecauseOfAd = isMobile ? isAdVisible : false;

        // Yukarı çık butonu: Sayfa aşağı kaydırılmışsa VE (reklam gizleme koşulu yoksa)
        if (isScrolled && !hideBecauseOfAd) {
            scrollTopBtn.classList.remove('translate-y-20', 'opacity-0', 'invisible', 'pointer-events-none');
        } else {
            scrollTopBtn.classList.add('translate-y-20', 'opacity-0', 'invisible', 'pointer-events-none');
        }

        // Aşağı in butonu: Sayfa en altta değilse VE (reklam gizleme koşulu yoksa)
        if (!isAtBottom && !hideBecauseOfAd) {
            scrollBottomBtn.classList.remove('translate-y-20', 'opacity-0', 'invisible', 'pointer-events-none');
        } else {
            scrollBottomBtn.classList.add('translate-y-20', 'opacity-0', 'invisible', 'pointer-events-none');
        }
    }

    // 1. Scroll Olayını Dinliyoruz
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY || window.pageYOffset;
        const documentHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
        
        isScrolled = scrollY > 300;
        isAtBottom = (window.innerHeight + scrollY) >= documentHeight - 150; 
        
        updateButtonsVisibility();
    }, { passive: true });

    // 2. Ekran Boyutu Değişimi (Resize) Dinliyoruz
    // Kullanıcı telefonu yan çevirirse veya masaüstünde pencereyi küçültürse durumlar güncellensin
    window.addEventListener('resize', () => {
        updateButtonsVisibility();
    });

    // 3. Reklam Alanını Dinliyoruz (Intersection Observer)
    const adElements = document.querySelectorAll('.content-ad'); 
    
    if (adElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            // Durumu değişen her bir reklamı Map'e kaydediyoruz veya güncelliyoruz
            entries.forEach(entry => {
                activeAds.set(entry.target, entry.isIntersecting);
            });

            // Hafızadaki tüm reklamları kontrol et: Ekranda en az bir aktif (true) reklam var mı?
            isAdVisible = Array.from(activeAds.values()).some(visible => visible);
            
            updateButtonsVisibility();
        }, {
            root: null,
            threshold: 0.1 // Reklamın %10'u bile ekrana girse butonları gizlemeye başlar
        });

        adElements.forEach(ad => {
            // Başlangıçta tüm reklamları görünmez (false) olarak Map'e ekleyelim
            activeAds.set(ad, false);
            observer.observe(ad);
        });
    }

    // --- Butonlara tıklama (scroll) işlevleri ---
    if (scrollTopBtn) {
        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    if (scrollBottomBtn) {
        scrollBottomBtn.addEventListener('click', () => {
            const documentHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
            window.scrollTo({ top: documentHeight, behavior: 'smooth' });
        });
    }
</script>
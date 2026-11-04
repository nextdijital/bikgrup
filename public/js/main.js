/**
 * BİKGRUP - Ana JavaScript Motoru
 * Çizgisel Mimari & Scrolled Navbar Dinleyicisi
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Akıllı Scrolled Navbar Kontrolü (Adler hero olan sayfalarda ilk girişte gizlidir, scroll ile belirir)
    const headerWrapper = document.querySelector('.header-wrapper');
    const adlerHero = document.querySelector('.adler-hero-viewport');

    if (headerWrapper && adlerHero) {
        const updateNavbar = () => {
            if (window.scrollY > 300) {
                headerWrapper.classList.add('header-visible');
            } else {
                headerWrapper.classList.remove('header-visible');
            }
        };
        window.addEventListener('scroll', updateNavbar, { passive: true });
        updateNavbar();
    }

    // 2. Mobil Tam Ekran Menü Açma/Kapama (Kullanıcı Talebi)
    const mobileToggles = document.querySelectorAll('.mobile-toggle');
    const mobileDrawer = document.querySelector('.mobile-menu-drawer');
    const mobileClose = document.querySelector('.mobile-menu-close, .mobile-menu-close-btn');

    window.closeMobileMenu = function() {
        const drawer = document.querySelector('.mobile-menu-drawer');
        if (drawer) {
            drawer.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    if (mobileToggles.length > 0 && mobileDrawer) {
        mobileToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                mobileDrawer.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        if (mobileClose) mobileClose.addEventListener('click', window.closeMobileMenu);
        mobileDrawer.addEventListener('click', (e) => {
            if (e.target === mobileDrawer) window.closeMobileMenu();
        });

        // ESC tuşuyla kapatma
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileDrawer.classList.contains('active')) {
                window.closeMobileMenu();
            }
        });
    }

    // 3. Scroll Reveal Animasyonları (IntersectionObserver)
    const revealElements = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -40px 0px'
        });

        revealElements.forEach(el => revealObserver.observe(el));
    } else {
        revealElements.forEach(el => el.classList.add('active'));
    }
});

// 4. Teklif Al Modal Fonksiyonları (Global)
window.openQuoteModal = function(productName) {
    const modal = document.getElementById('quoteModal');
    if (modal) {
        // Eğer ürün adı gönderildiyse seçim alanını otomatik doldur
        if (productName && typeof productName === 'string') {
            const catInput = document.getElementById('quoteCategory');
            const catText = document.getElementById('quoteCategoryText');
            if (catInput && catText) {
                catInput.value = productName;
                catText.textContent = productName;
                catText.classList.add('has-value');
            }
        }

        modal.classList.add('active');
    }
};

window.closeQuoteModal = function() {
    const modal = document.getElementById('quoteModal');
    if (modal) {
        modal.classList.remove('active');
    }
};

// Modal açıkken arkaplan kaymasını ve zincirleme scroll sıçramasını önleyen izolasyon
document.addEventListener('DOMContentLoaded', function() {
    const quoteModalEl = document.getElementById('quoteModal');
    if (quoteModalEl) {
        quoteModalEl.addEventListener('wheel', function(e) {
            // Eğer tekerlek veya scroll işlemi açılır dropdown içindeyse kesinlikle engelleme!
            if (e.target && e.target.closest && e.target.closest('.custom-select-dropdown')) {
                return;
            }
            const card = quoteModalEl.querySelector('.quote-modal-card');
            if (!card || !card.contains(e.target)) {
                e.preventDefault();
            } else {
                const isAtTop = card.scrollTop <= 0 && e.deltaY < 0;
                const isAtBottom = (card.scrollHeight - card.scrollTop <= card.clientHeight + 1) && e.deltaY > 0;
                if (isAtTop || isAtBottom) {
                    e.preventDefault();
                }
            }
        }, { passive: false });

        quoteModalEl.addEventListener('touchmove', function(e) {
            // Dokunmatik kaydırma dropdown içindeyse engelleme
            if (e.target && e.target.closest && e.target.closest('.custom-select-dropdown')) {
                return;
            }
            const card = quoteModalEl.querySelector('.quote-modal-card');
            if (!card || !card.contains(e.target)) {
                e.preventDefault();
            }
        }, { passive: false });
    }
});

// Escape tuşu ile modal kapatma
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        window.closeQuoteModal();
    }
});

window.resetQuoteCategory = function() {
    const hiddenInput = document.getElementById('quoteCategory');
    const label = document.getElementById('quoteCategoryText');
    const dropdown = document.getElementById('quoteCategoryDropdown');
    const items = document.querySelectorAll('.custom-select-item');
    
    if (hiddenInput) hiddenInput.value = '';
    if (label) {
        label.textContent = label.getAttribute('data-placeholder') || 'Ürün veya Kategori seçiniz...';
        label.classList.remove('has-value');
    }
    items.forEach(it => it.classList.remove('selected'));
    if (dropdown) dropdown.classList.remove('active');
};

window.submitQuoteRequest = async function(e) {
    e.preventDefault();
    const form = document.getElementById('quoteRequestForm');
    const alertBox = document.getElementById('quoteFormAlert');
    const submitBtn = document.getElementById('quoteSubmitBtn');
    
    if (!form || !alertBox || !submitBtn) return;

    const catInput = document.getElementById('quoteCategory');
    if (!catInput || !catInput.value.trim()) {
        alertBox.style.display = 'block';
        alertBox.style.background = '#fef2f2';
        alertBox.style.color = '#b91c1c';
        alertBox.style.border = '1px solid #fecaca';
        const isEn = document.documentElement.lang === 'en' || window.location.pathname.includes('/en');
        alertBox.innerHTML = '⚠️ ' + (isEn ? 'Please select a product or category.' : 'Lütfen bir ürün veya kategori seçiniz.');
        document.getElementById('quoteCategoryTrigger')?.focus();
        return;
    }

    const originalBtnHtml = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span>⏳</span><span>Gönderiliyor...</span>';
    alertBox.style.display = 'none';

    try {
        const formData = new FormData(form);
        const baseUrl = window.location.origin + '/bikgrup';
        const response = await fetch(baseUrl + '/teklif-al', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alertBox.style.display = 'block';
            alertBox.style.background = '#f0fdf4';
            alertBox.style.color = '#15803d';
            alertBox.style.border = '1px solid #bbf7d0';
            alertBox.innerHTML = '✅ ' + result.message;
            form.reset();
            window.resetQuoteCategory();

            setTimeout(() => {
                window.closeQuoteModal();
                alertBox.style.display = 'none';
            }, 3500);
        } else {
            alertBox.style.display = 'block';
            alertBox.style.background = '#fef2f2';
            alertBox.style.color = '#b91c1c';
            alertBox.style.border = '1px solid #fecaca';
            alertBox.innerHTML = '⚠️ ' + (result.message || 'Bir hata oluştu.');
        }
    } catch (err) {
        alertBox.style.display = 'block';
        alertBox.style.background = '#fef2f2';
        alertBox.style.color = '#b91c1c';
        alertBox.style.border = '1px solid #fecaca';
        alertBox.innerHTML = '⚠️ Bağlantı hatası oluştu. Lütfen tekrar deneyiniz.';
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnHtml;
    }
};

// Custom Select Dropdown logic
document.addEventListener('DOMContentLoaded', () => {
    const dropdown = document.getElementById('quoteCategoryDropdown');
    const trigger = document.getElementById('quoteCategoryTrigger');
    const label = document.getElementById('quoteCategoryText');
    const hiddenInput = document.getElementById('quoteCategory');
    const items = document.querySelectorAll('.custom-select-item');

    if (dropdown && trigger && hiddenInput) {
        if (label) {
            label.setAttribute('data-placeholder', label.textContent.trim());
        }

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = dropdown.classList.toggle('active');
            trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        const listEl = document.getElementById('quoteCategoryList');
        if (listEl) {
            listEl.addEventListener('wheel', (e) => {
                e.stopPropagation();
            }, { passive: true });
            listEl.addEventListener('touchmove', (e) => {
                e.stopPropagation();
            }, { passive: true });
        }

        items.forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                const val = item.getAttribute('data-value');
                const text = item.querySelector('.item-text') ? item.querySelector('.item-text').textContent.trim() : val;
                
                hiddenInput.value = val;
                if (label) {
                    label.textContent = text;
                    label.classList.add('has-value');
                }
                
                items.forEach(it => it.classList.remove('selected'));
                item.classList.add('selected');
                
                dropdown.classList.remove('active');
                trigger.setAttribute('aria-expanded', 'false');
            });
        });

        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (typeof window.closeQuoteModal === 'function') {
            window.closeQuoteModal();
        }
    }
});

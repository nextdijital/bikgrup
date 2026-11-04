/**
 * BİKGRUP - xpanel Yönetim Paneli JS Motoru
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Canlı Renk Seçici Takibi
    const colorInputs = document.querySelectorAll('.theme-color-input');
    
    const updateSimulation = (name, val) => {
        if (name === 'theme_primary') {
            const btn = document.getElementById('preview-btn-primary');
            if (btn) btn.style.background = val;
            const heading = document.getElementById('preview-heading');
            if (heading) heading.style.color = val;
        } else if (name === 'theme_secondary') {
            const btn = document.getElementById('preview-btn-secondary');
            if (btn) btn.style.background = val;
        } else if (name === 'theme_accent') {
            const accent = document.getElementById('preview-accent');
            if (accent) accent.style.background = val;
        } else if (name === 'theme_text_muted') {
            const txt = document.getElementById('preview-text');
            if (txt) txt.style.color = val;
        } else if (name === 'theme_card_bg') {
            const box = document.getElementById('preview-simulation-container');
            if (box) box.style.background = val;
        } else if (name === 'theme_border_radius') {
            const btn1 = document.getElementById('preview-btn-primary');
            const btn2 = document.getElementById('preview-btn-secondary');
            if (btn1) btn1.style.borderRadius = val;
            if (btn2) btn2.style.borderRadius = val;
        }
    };

    colorInputs.forEach(input => {
        const textInput = document.querySelector(`input[data-color-sync="${input.name}"]`);

        // Başlangıçta mevcut değerleri simülasyona yansıt
        updateSimulation(input.name, input.value);

        input.addEventListener('input', (e) => {
            if (textInput) {
                textInput.value = e.target.value;
            }
            updateSimulation(input.name, e.target.value);
        });

        if (textInput) {
            textInput.addEventListener('input', (e) => {
                let val = e.target.value.trim();
                if (!val.startsWith('#') && /^[0-9A-Fa-f]{6}$/.test(val)) {
                    val = '#' + val;
                }
                if (/^#[0-9A-F]{6}$/i.test(val)) {
                    input.value = val;
                    updateSimulation(input.name, val);
                }
            });

            textInput.addEventListener('blur', (e) => {
                let val = e.target.value.trim();
                if (!val.startsWith('#') && /^[0-9A-Fa-f]{6}$/.test(val)) {
                    val = '#' + val;
                    textInput.value = val;
                }
                if (/^#[0-9A-F]{6}$/i.test(val)) {
                    input.value = val;
                    updateSimulation(input.name, val);
                }
            });
        }
    });

    // Radius input takibi
    const radiusInput = document.querySelector('input[name="theme_border_radius"]');
    if (radiusInput) {
        updateSimulation('theme_border_radius', radiusInput.value);
        radiusInput.addEventListener('input', (e) => {
            updateSimulation('theme_border_radius', e.target.value);
        });
    }

    // Accent input takibi
    const accentInput = document.querySelector('input[name="theme_accent"]');
    if (accentInput) {
        updateSimulation('theme_accent', accentInput.value);
    }

    // 2. Silme İşlemleri Onayı
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const message = btn.getAttribute('data-confirm-delete') || 'Bu kaydı silmek istediğinize emin misiniz?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
});

/**
 * Modal Açma / Kapatma
 */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

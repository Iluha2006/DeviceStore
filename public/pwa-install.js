
let deferredPrompt;
const installBtn = document.getElementById('pwa-install-btn');

function showInstallButton() {
    if (installBtn) {
        installBtn.style.display = 'block';
    }
}

function hideInstallButton() {
    if (installBtn) {
        installBtn.style.display = 'none';
    }
}


if (window.matchMedia('(display-mode: standalone)').matches || navigator.standalone) {
    console.log('App already installed');
    hideInstallButton();
} else {
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        showInstallButton();
    });
}

if (installBtn) {
    installBtn.addEventListener('click', async () => {
        if (!deferredPrompt) return;

        try {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`User response: ${outcome}`);

            hideInstallButton();
            deferredPrompt = null;
        } catch (error) {
            console.error('Install error:', error);
        }
    });
}

window.addEventListener('appinstalled', () => {
    console.log('PWA was installed');
    hideInstallButton();
});
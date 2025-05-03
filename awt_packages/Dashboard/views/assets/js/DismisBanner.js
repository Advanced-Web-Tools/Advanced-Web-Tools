function dismissBanner() {
    localStorage.setItem('welcomeBannerDismissed', 'true');
    document.querySelector('.welcome-banner').style.display = 'none';
}

window.onload = function () {
    if (localStorage.getItem('welcomeBannerDismissed') === 'true') {
        document.querySelector('.welcome-banner').style.display = 'none';
    }
};

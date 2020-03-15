$('.theme-switch input[type="checkbox"]').change(function(e) {
    if (e.target.checked) {
        theme_toggle_light()
    }
    else {
        theme_toggle_dark()
    }
});

const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;

if (currentTheme) {
    document.documentElement.setAttribute('data-theme', currentTheme);

    if (currentTheme === 'light') {
        $('.theme-switch input[type="checkbox"]').prop('checked', true);
        theme_toggle_light()
    } else theme_toggle_dark()
}
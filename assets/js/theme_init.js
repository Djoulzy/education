window.theme_toggle_light = function() {
    $("nav").removeClass('navbar-dark')
    $("nav").addClass('navbar-light')
    document.documentElement.setAttribute('data-theme', 'light');
    localStorage.setItem('theme', 'light'); //add this
    $(".bbChart").each(function(index, value) {
        window[$(this).attr('id')].light()
    })
}

window.theme_toggle_dark = function() {
    $("nav").removeClass('navbar-light')
    $("nav").addClass('navbar-dark')
    document.documentElement.setAttribute('data-theme', 'dark');
    localStorage.setItem('theme', 'dark'); //add this
    $(".bbChart").each(function(index, value) {
        window[$(this).attr('id')].dark()
    })
}

window.chartThemeSwitcher = function(chartName) {
    let currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;
    if (currentTheme === 'light') window[chartName].light()
    else window[chartName].dark()
}

const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;

if (currentTheme) {
    document.documentElement.setAttribute('data-theme', currentTheme);

    if (currentTheme === 'light') theme_toggle_light()
    else theme_toggle_dark()
}
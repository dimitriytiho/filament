document.addEventListener('DOMContentLoaded', function() {

    // Необходимые данные
    const offset = 20,
        heightScreen = window.innerHeight,
        widthScreen = window.innerWidth || document.body.clientWidth


    // Селекторы в коде
    const btnUp = document.getElementById('btn-up')


    // Событие скролл
    $(window).on('scroll', function () {
        const scrollTop = $(window).scrollTop() || document.documentElement.scrollTop, // Верхняя позиция скролла
            heightWindow = window.innerHeight // Высота окна браузера

        // pageYOffset Динамическая позиция скролла у верхней кромки


        if (btnUp) {
            if (scrollTop > 300 && !btnUp.classList.contains('scale-in')) {
                btnUp.classList.remove('scale-out')
                btnUp.classList.add('scale-in')
            } else if (scrollTop < 300 && !btnUp.classList.contains('scale-out')) {
                btnUp.classList.remove('scale-in')
                btnUp.classList.add('scale-out')
            }
        }


        // Прилипающее меню
        /*const stickyMenu = document.getElementById('sticky_menu')
        if (stickyMenu) {
            const stickyMenuHeight = stickyMenu.offsetHeight
            if (stickyMenuHeight && document.body.clientWidth > 992 && pageYOffset > stickyMenuHeight) {
                stickyMenu.classList.add('sticky_menu')
            } else {
                stickyMenu.classList.remove('sticky_menu')
            }
        }*/
    })

}, false)

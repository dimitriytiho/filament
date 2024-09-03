if (typeof recaptchaPublicKey !== 'undefined' && typeof grecaptcha !== 'undefined') {
    grecaptcha.ready(function() {
        grecaptcha.execute(recaptchaPublicKey, {action:'homepage'}).then(function(token) {
            const grecaptchaBody = document.querySelector('body')
            let issetRecaptcha = false

            // Если на теге есть класс add_recaptcha_input добавляем скрытый input recaptcha со значение token
            const addGrecaptchaFormInputs = document.querySelectorAll('.add_recaptcha_input')
            if (addGrecaptchaFormInputs.length) {
                issetRecaptcha = true
                addGrecaptchaFormInputs.forEach(function (el) {
                    const input = document.createElement('input')
                    input.type = 'hidden'
                    input.name = 'recaptcha'
                    input.classList.add('recaptcha_input')
                    input.value = token
                    el.append(input)
                })
            }

            // На класс add_recaptcha_token добавляем значение token
            const grecaptchaIds = document.querySelectorAll('.add_recaptcha_token')
            if (grecaptchaIds.length) {
                issetRecaptcha = true
                grecaptchaIds.forEach(function (el) {
                    el.classList.add('recaptcha_input')
                    el.value = token
                })
            }

            if (grecaptchaBody) {
                //grecaptchaBody.dataset.recaptcha = token
                if (issetRecaptcha) {
                    grecaptchaBody.classList.add('isset_recaptcha')
                }
            }
        })
    })
}

// Функция обновления токена в input, если от сервера пришла ошибка
function updateGrecaptchaToken() {
    if (typeof recaptchaPublicKey !== 'undefined' && typeof grecaptcha !== 'undefined') {
        grecaptcha.execute(recaptchaPublicKey, {action:'homepage'}).then(function(token) {
            const grecaptchaFormInputs = document.querySelectorAll('.recaptcha_input')
            if (grecaptchaFormInputs.length) {
                grecaptchaFormInputs.forEach(function (el) {
                    el.value = token
                })
            }
        })
    }
}

// https://jqueryvalidation.org
import $ from 'jquery'
import 'jquery-validation'

let validateClass = 'validate'

$.validator.setDefaults({
    // Удаление фокуса с input
    onfocusout: function (element) {
        this.element(element)
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback')
        element.closest('div').append(error)
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid')
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid')
    },

    // Действия перед валидацией
    //beforeSubmit: function () {},

    // Действия после успешного ввода
    submitHandler: function (form) {
        // Заблокируем кнопку
        $(form)
            .find('[type=submit]')
            .attr('disabled', true)
            .addClass('disabled')
        // Включим спиннер
        if (typeof spinner !== 'undefined') {
            $(form).find('[type=submit]')
                .addClass('d-inline-flex')
                .append(spinner)
        }
        return false
    }
})


// Добавляем валидатор для номера телефона
$.validator.methods.checkTel = function (value, element) {
    return this.optional(element) || /^[\+\(\)\- 0-9]+$/.test(value)
}
// Добавляем валидатор для ip
$.validator.methods.checkIp = function (value, element) {
    return this.optional(element) || /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(value)
}


// Запуск валидации
validateClass = '.' + validateClass
$(validateClass).validate()


// Дополнительные правила для всех форм валидации
$(validateClass + ' input[type=tel]').rules('add', {
    checkTel: true
})
$(validateClass + ' input[type=email]').rules('add', {
    email: true
})
/*$(validateClass + ' input[name=ip]').rules('add', {
    checkIp: true
}, "Please enter a valid IP address")*/
$(validateClass + ' input[type=password]').rules('add', {
    minlength: 8
})
$(validateClass + ' input[name=password_confirmation]').rules('add', {
    minlength: 8,
    equalTo: '#password'
})



// Переводы сообщений
$.extend($.validator.messages, {
    required: "Это поле необходимо заполнить.",
    remote: "Пожалуйста, введите правильное значение.",
    email: "Пожалуйста, введите корректный адрес электронной почты.",
    url: "Пожалуйста, введите корректный URL.",
    date: "Пожалуйста, введите корректную дату.",
    dateISO: "Пожалуйста, введите корректную дату в формате ISO.",
    number: "Пожалуйста, введите число.",
    digits: "Пожалуйста, вводите только цифры.",
    creditcard: "Пожалуйста, введите правильный номер кредитной карты.",
    equalTo: "Пожалуйста, введите такое же значение ещё раз.",
    extension: "Пожалуйста, выберите файл с правильным расширением.",
    maxlength: $.validator.format("Пожалуйста, введите не больше {0} символов."),
    minlength: $.validator.format("Пожалуйста, введите не меньше {0} символов."),
    rangelength: $.validator.format("Пожалуйста, введите значение длиной от {0} до {1} символов."),
    range: $.validator.format("Пожалуйста, введите число от {0} до {1}."),
    max: $.validator.format("Пожалуйста, введите число, меньшее или равное {0}."),
    min: $.validator.format("Пожалуйста, введите число, большее или равное {0}."),
    checkTel: "Пожалуйста, введите правильный номер телефона",
    checkIp: "Пожалуйста, введите правильный ip адрес"
})

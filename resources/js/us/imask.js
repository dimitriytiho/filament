import IMask from 'imask'

// Маска ввода телефона
const maskPhones = document.querySelectorAll('form input[type=tel]')
/*const maskOptions = {
    mask: '+{7}(000)000-00-00'
    // lazy: false // Чтобы маска была сразу видна
}*/

if (maskPhones.length) {
    //let mask = IMask(el, maskOptions)
    maskPhones.forEach(function (el) {
        let mask = IMask(
            el, {
                mask: [
                    {
                        mask: '+0(000)000-00-00',
                        startsWith: '7',
                        lazy: true,
                        country: 'Russia'
                    },
                    {
                        mask: '+7(900)000-00-00',
                        startsWith: '9',
                        lazy: true,
                        country: 'Russia'
                    }
                ],
                dispatch: function (value, dynamicMasked) {
                    const number = (dynamicMasked.value + value).replace(/\D/g, '')
                    return dynamicMasked.compiledMasks.find(function (m) {
                        return number.indexOf(m.startsWith) === 0
                    })
                },
                prepare: function (value, masked) {
                    if (masked.value === '' && ['0', '1', '2', '3', '4', '5', '6', '7', '8'].includes(value)) {
                        return '7'
                    }
                    return value
                }
            }
        )
    })
}

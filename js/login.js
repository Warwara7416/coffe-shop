document.addEventListener('DOMContentLoaded', () => {
    const phoneInput = document.getElementById('phone');
    const passwordInput = document.getElementById('password');
    const authForm = document.getElementById('authForm');

    const regPhoneInput = document.getElementById('reg-phone');
    const regPasswordInput = document.getElementById('reg-password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const regForm = document.getElementById('regForm');

    const toggleFormButton = document.getElementById('toggleForm');
    const toggleFormBackButton = document.getElementById('toggleFormBack');

    const errorMessage = document.getElementById('error-message');

    const logoutButton = document.getElementById('logoutButton');

    // Маска для номера телефона
    function formatPhoneInput(input) {
        input.addEventListener('input', () => {
            let value = input.value.replace(/\D/g, '');
            let formattedValue = '+7 (';

            if (value.length > 1) formattedValue += value.substring(1, 4);
            if (value.length >= 4) formattedValue += ') ' + value.substring(4, 7);
            if (value.length >= 7) formattedValue += '-' + value.substring(7, 9);
            if (value.length >= 9) formattedValue += '-' + value.substring(9, 11);

            input.value = formattedValue;
        });
    }

    formatPhoneInput(phoneInput);
    formatPhoneInput(regPhoneInput);

    // Валидация пароля
    function validatePasswordInput(input) {
        input.addEventListener('input', () => {
            const invalidChars = /[^a-zA-Z0-9]/;
            if (invalidChars.test(input.value)) {
                input.setCustomValidity('Пароль может содержать только латинские буквы и цифры.');
            } else {
                input.setCustomValidity('');
            }
        });
    }

    validatePasswordInput(passwordInput);
    validatePasswordInput(regPasswordInput);
    validatePasswordInput(confirmPasswordInput);

    // Показать сообщение об ошибке
    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
    }

    // Очистить сообщение об ошибке
    function clearError() {
        errorMessage.textContent = '';
        errorMessage.style.display = 'none';
    }

    // Обработка отправки формы авторизации
    authForm.addEventListener('submit', (event) => {
        event.preventDefault();
        clearError();

        const phoneValue = phoneInput.value;
        const passwordValue = passwordInput.value;

        // Проверка номера телефона
        const phonePattern = /^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/;
        if (!phonePattern.test(phoneValue)) {
            showError('Введите корректный номер телефона.');
            return;
        }

        // Проверка пароля
        const invalidChars = /[^a-zA-Z0-9]/;
        if (invalidChars.test(passwordValue)) {
            showError('Пароль может содержать только латинские буквы и цифры.');
            return;
        }

        // Отправка формы, если все проверки пройдены
        authForm.submit();
    });

    // Обработка отправки формы регистрации
    regForm.addEventListener('submit', (event) => {
        event.preventDefault();
        clearError();

        const phoneValue = regPhoneInput.value;
        const passwordValue = regPasswordInput.value;
        const confirmPasswordValue = confirmPasswordInput.value;

        // Проверка номера телефона
        const phonePattern = /^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/;
        if (!phonePattern.test(phoneValue)) {
            showError('Введите корректный номер телефона.');
            return;
        }

        // Проверка пароля
        const invalidChars = /[^a-zA-Z0-9]/;
        if (invalidChars.test(passwordValue)) {
            showError('Пароль может содержать только латинские буквы и цифры.');
            return;
        }

        // Проверка минимальной длины пароля
        if (passwordValue.length < 12) {
            showError('Пароль должен состоять минимум из 12 символов.');
            return;
        }

        // Проверка совпадения паролей
        if (passwordValue !== confirmPasswordValue) {
            showError('Пароли не совпадают.');
            return;
        }

        // Отправка формы, если все проверки пройдены
        regForm.submit();
    });

    // Переключение между формами
    toggleFormButton.addEventListener('click', () => {
        clearError();
        authForm.style.display = 'none';
        regForm.style.display = 'block';
    });

    toggleFormBackButton.addEventListener('click', () => {
        clearError();
        regForm.style.display = 'none';
        authForm.style.display = 'block';
    });

    // Обработка выхода из аккаунта
    if (logoutButton) {
        logoutButton.addEventListener('click', () => {
            fetch('./security/logout.php')
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        showError('Ошибка при выходе из системы.');
                    }
                })
                .catch(error => {
                    showError('Ошибка при выходе из системы.');
                });
        });
    }
});

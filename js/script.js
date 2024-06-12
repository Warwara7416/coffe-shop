document.addEventListener('DOMContentLoaded', () => {
    const cartBtn = document.getElementById('cart-btn');
    const cartPopup = document.getElementById('cart-popup');
    const cartContainer = document.getElementById('cart-container');
    const orderBtn = cartPopup.querySelector('.btn');

    cartBtn.addEventListener('click', () => {
        cartPopup.classList.toggle('active');
    });

    const menuItems = document.querySelectorAll('.menu .box');
    let cartItems = [];

    function updateCart() {
        cartContainer.innerHTML = '';
        cartItems.forEach((item, index) => {
            const cartItem = document.createElement('div');
            cartItem.classList.add('cart-item');
            cartItem.dataset.id = item.id;
            cartItem.dataset.name = item.name;
            cartItem.dataset.price = item.price;
            cartItem.dataset.quantity = item.quantity;

            const itemDetails = document.createElement('div');
            itemDetails.classList.add('item-details');
            itemDetails.innerHTML = `<h4>${item.name}</h4><p>${item.price.toFixed(2)}&#8381;</p>`;

            const quantityControls = document.createElement('div');
            quantityControls.classList.add('quantity-controls');
            quantityControls.innerHTML = `
                <button class="decrease">-</button>
                <span>${item.quantity}</span>
                <button class="increase">+</button>
            `;

            quantityControls.querySelector('.decrease').addEventListener('click', () => {
                if (item.quantity > 1) {
                    item.quantity--;
                    updateCart();
                } else {
                    cartItems.splice(index, 1);
                    updateCart();
                }
            });

            quantityControls.querySelector('.increase').addEventListener('click', () => {
                item.quantity++;
                updateCart();
            });

            cartItem.appendChild(itemDetails);
            cartItem.appendChild(quantityControls);
            cartContainer.appendChild(cartItem);
        });

        if (cartItems.length === 0) {
            cartContainer.innerHTML = '<p>Ваша корзина пуста</p>';
            orderBtn.style.display = 'none';
        } else {
            orderBtn.style.display = 'block';
        }
    }

    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            const id = item.getAttribute('data-id');
            const name = item.getAttribute('data-name');
            const price = parseFloat(item.getAttribute('data-price'));
            const existingItem = cartItems.find(cartItem => cartItem.id === id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cartItems.push({ id, name, price, quantity: 1 });
            }
            updateCart();
        });
    });

    updateCart();

    document.querySelector('#order-btn').addEventListener('click', function() {
        let cartData = [];
        document.querySelectorAll('#cart-container .cart-item').forEach(item => {
            cartData.push({
                id: item.dataset.id,
                name: item.dataset.name,
                price: parseFloat(item.dataset.price),
                quantity: parseInt(item.dataset.quantity)
            });
        });

        fetch('/coffee-shop-website-design-main/functions/save_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(cartData)
        }).then(response => response.text()).then(data => {
            try {
                const json = JSON.parse(data);
                if (json.success) {
                    window.location.href = '/coffee-shop-website-design-main/checkout.php';
                } else {
                    alert('Ошибка сохранения корзины.');
                }
            } catch (error) {
                console.error('Ошибка парсинга JSON:', error);
                console.log('Ответ сервера:', data);
            }
        }).catch(error => {
            console.error('Ошибка:', error);
        });
    });
});

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Keranjang</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .item {
            transition: all .3s ease;
        }

        .item:hover {
            transform: scale(1.02);
        }
    </style>
</head>

<body class="bg-orange-50 p-6">

    <!-- HEADER -->
    <div class="flex items-center gap-3 mb-6">
        <button onclick="goBack()" class="text-orange-500">←</button>
        <h1 class="text-xl font-bold">Keranjang</h1>
    </div>

    <!-- LIST -->
    <div id="cartItems"></div>

    <!-- TOTAL -->
    <div class="bg-white p-4 rounded-xl shadow mt-6">
        <p>Total</p>
        <p id="total" class="text-xl font-bold text-orange-500">Rp 0</p>
    </div>

    <!-- BUTTON -->
    <button onclick="checkout()" class="w-full mt-4 bg-orange-500 text-white py-3 rounded-xl font-bold">
        Lanjut Checkout
    </button>

    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        function renderCart() {

            const el = document.getElementById('cartItems');

            if (cart.length === 0) {
                el.innerHTML = `<p class="text-center text-gray-500">Keranjang kosong</p>`;
                return;
            }

            let total = 0;

            el.innerHTML = cart.map((item, index) => {

                total += item.price * item.quantity;

                return `
        <div class="item bg-white p-4 rounded-xl shadow mb-3 flex justify-between items-center">

            <div>
                <p class="font-bold">${item.name}</p>
                <p class="text-sm text-gray-500">
                    Rp ${item.price.toLocaleString('id-ID')}
                </p>
            </div>

            <div class="flex items-center gap-2">

                <button onclick="changeQty(${index}, -1)" class="px-2 bg-gray-200">-</button>
                <span>${item.quantity}</span>
                <button onclick="changeQty(${index}, 1)" class="px-2 bg-gray-200">+</button>

                <button onclick="removeItem(${index})" class="text-red-500 ml-2">✕</button>

            </div>

        </div>
        `;
            }).join('');

            document.getElementById('total').textContent =
                "Rp " + total.toLocaleString('id-ID');
        }

        // qty
        function changeQty(index, val) {
            cart[index].quantity += val;

            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }

        // hapus
        function removeItem(index) {
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }

        // back
        function goBack() {
            window.location.href = '/customer/home';
        }

        // checkout
        function checkout() {
            window.location.href = '/customer/checkout';
        }

        renderCart();
    </script>

</body>

</html>

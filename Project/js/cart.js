document.addEventListener('DOMContentLoaded', () => {
    const productList = Array.from(document.getElementsByClassName('cart-list-item'));
    const totalPriceElement = document.getElementById('total-price');
    const currencySelector = document.getElementById("currency");
    
    const exchangeRates = {
    ALL: 1,
    EUR: 0.0094,
    USD: 0.0103,
    };

    const currencySymbols = {
        ALL: "L",
        EUR: "€",
        USD: "$",
    };
    
    function updateTotal() {
        let total = 0;
        for (const product of productList) {
            const amountInput = product.querySelector('.cart-amount-picker');
            const priceElem = product.querySelector('.price');
            const unitPrice = parseFloat(priceElem.dataset.price);
            const amount = parseInt(amountInput.value) || 1;
            total += unitPrice * amount;
            priceElem.innerText = `${(unitPrice * amount).toFixed(2)} L`;
        }
        totalPriceElement.innerText = `${total.toFixed(2)} L`;
        totalPriceElement.setAttribute('data-price', total.toFixed(2));

        const selected = currencySelector.value;
        const rate = exchangeRates[selected];
        const symbol = currencySymbols[selected];
        const currencyHidden = document.getElementById('currency-hidden');
        if (currencyHidden) {
            currencyHidden.value = selected;
        }

        document.querySelectorAll(".price").forEach((price) => {
            const basePrice = parseFloat(price.getAttribute("data-price"));
            const converted = (basePrice * rate).toFixed(2);
            price.textContent = `${converted} ${symbol}`;
        });
    }

    for (const product of productList) {
        const amountInput = product.querySelector('.cart-amount-picker');
        amountInput.addEventListener('input', updateTotal);
    }

    updateTotal();
});
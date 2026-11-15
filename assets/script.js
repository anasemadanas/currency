// Client-side only: swap button + flag next to each currency select.
// Flags come from each <option data-flag>, so the currency list lives only in config.php.

const fromCurrency = document.getElementById("fromCurrency");
const toCurrency = document.getElementById("toCurrency");

function updateFlag(select) {
    const flag = select.selectedOptions[0].dataset.flag;
    document.getElementById(select.dataset.flagTarget).textContent = flag;
}

document.getElementById("swapButton").addEventListener("click", function () {
    [fromCurrency.value, toCurrency.value] = [toCurrency.value, fromCurrency.value];
    updateFlag(fromCurrency);
    updateFlag(toCurrency);
});

fromCurrency.addEventListener("change", function () { updateFlag(fromCurrency); });
toCurrency.addEventListener("change", function () { updateFlag(toCurrency); });

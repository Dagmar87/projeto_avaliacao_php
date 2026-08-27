document.addEventListener('DOMContentLoaded', function () {
	const formService = document.getElementById('formService');
	if (formService) {
		formService.addEventListener('submit', function (e) {
			const priceInput = document.getElementById('price');
			const priceVal = priceInput.value.replace(',', '.');

			if (isNaN(priceVal) || parseFloat(priceVal) <= 0) {
				alert('Por favor, informe um valor de preço válido.');
				e.preventDefault();
			}
		});
	}
});
<!-- components/currency_converter.php -->
<div class="card mb-4">
    <div class="card-header">
        <h5><i class="fas fa-exchange-alt me-2"></i>Convertisseur de devises</h5>
    </div>
    <div class="card-body">
        <form id="currencyConverterForm" class="row g-2">
            <div class="col-md-4">
                <input type="number" class="form-control" id="amount" placeholder="Montant" step="0.01">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="fromCurrency">
                    <option value="MAD">DH Marocain</option>
                    <option value="EUR">Euro</option>
                    <option value="USD">Dollar US</option>
                    <option value="CAD">Dollar Canadien</option>
                </select>
            </div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-outline-secondary w-100" id="swapCurrencies">
                    <i class="fas fa-exchange-alt"></i>
                </button>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="toCurrency">
                    <option value="EUR">Euro</option>
                    <option value="USD">Dollar US</option>
                    <option value="MAD">DH Marocain</option>
                    <option value="CAD">Dollar Canadien</option>
                </select>
            </div>
            <div class="col-md-12 mt-3">
                <div class="alert alert-info" id="conversionResult">
                    Entrez un montant à convertir
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const convert = () => {
        const amount = parseFloat(document.getElementById('amount').value);
        const from = document.getElementById('fromCurrency').value;
        const to = document.getElementById('toCurrency').value;
        
        if (isNaN(amount)) {
            document.getElementById('conversionResult').textContent = 'Entrez un montant valide';
            return;
        }
        
        fetch(`/api/convert_currency.php?amount=${amount}&from=${from}&to=${to}`)
            .then(response => response.json())
            .then(data => {
                const result = `${amount} ${from} = ${data.convertedAmount.toFixed(2)} ${to}`;
                document.getElementById('conversionResult').textContent = result;
            });
    };
    
    document.getElementById('amount').addEventListener('input', convert);
    document.getElementById('fromCurrency').addEventListener('change', convert);
    document.getElementById('toCurrency').addEventListener('change', convert);
    
    document.getElementById('swapCurrencies').addEventListener('click', function() {
        const from = document.getElementById('fromCurrency');
        const to = document.getElementById('toCurrency');
        const temp = from.value;
        from.value = to.value;
        to.value = temp;
        convert();
    });
});
</script>
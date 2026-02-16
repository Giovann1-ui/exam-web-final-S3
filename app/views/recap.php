<?php 
$csp_nonce = $csp_nonce ?? '';
?>
<?php include __DIR__ . '/layouts/navigation.php'; ?>

<title>Récapitulatif - BNGRC</title>
<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<style nonce="<?= $csp_nonce ?>">
    .stats-card {
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .stats-card h6 {
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 10px;
        opacity: 0.9;
    }
    
    .stats-card h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
    
    .page-header {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
    }
    
    .page-header h1 {
        color: var(--primary-color);
        font-size: 1.75rem;
        font-weight: 600;
        margin: 0;
    }
    
    .btn-primary {
        padding: 10px 30px;
        font-weight: 500;
    }
</style>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <h1><i class="bi bi-graph-up"></i> Récapitulatif des Besoins</h1>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="stats-card bg-info text-white">
                    <h6>Besoins Totaux</h6>
                    <h2 id="total-montant">0 Ar</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card bg-success text-white">
                    <h6>Montant Satisfait</h6>
                    <h2 id="satisfait-montant">0 Ar</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card bg-danger text-white">
                    <h6>Montant Restant</h6>
                    <h2 id="restant-montant">0 Ar</h2>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <button type="button" class="btn btn-primary" id="actualiser-btn">
                <i class="bi bi-arrow-clockwise"></i> Actualiser
            </button>
        </div>
    </div>
</div>

<script nonce="<?= $csp_nonce ?>">
    // French currency formatter with thousands separator and two decimals
    const formatter = new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    function formatAmount(value) {
        const num = Number(value);
        if (!isFinite(num)) return formatter.format(0);
        return formatter.format(num);
    }

    function refreshData() {
        fetch('/recap/json')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-montant').innerText = formatAmount(data.total);
                document.getElementById('satisfait-montant').innerText = formatAmount(data.satisfait);
                document.getElementById('restant-montant').innerText = formatAmount(data.restant);
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    window.addEventListener('load', function () {
        const btn = document.getElementById('actualiser-btn');
        if (btn) btn.addEventListener('click', refreshData);
        refreshData();
    });
</script>
</body>
</html>

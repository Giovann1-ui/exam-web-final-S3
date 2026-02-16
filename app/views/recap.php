<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif</title>
    <link href="../../public/assets/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Récapitulatif des Besoins</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Besoins Totaux
                    </div>
                    <div class="card-body">
                        <h5 class="card-title" id="total-montant">0 €</h5>
                        <p class="card-text">Montant total des besoins.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Satisfaits
                    </div>
                    <div class="card-body">
                        <h5 class="card-title" id="satisfait-montant">0 €</h5>
                        <p class="card-text">Montant satisfait via distributions et achats.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Restants
                    </div>
                    <div class="card-body">
                        <h5 class="card-title" id="restant-montant">0 €</h5>
                        <p class="card-text">Montant restant à couvrir.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <button type="button" class="btn btn-primary" id="actualiser-btn">Actualiser</button>
        </div>
    </div>
<!--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->
    <script>
        function refreshData() {
            fetch('/recap/json')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-montant').innerText = data.total + ' €';
                    document.getElementById('satisfait-montant').innerText = data.satisfait + ' €';
                    document.getElementById('restant-montant').innerText = data.restant + ' €';
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        document.getElementById('actualiser-btn').addEventListener('click', refreshData);

        window.onload = refreshData;
    </script>



</body>
</html>
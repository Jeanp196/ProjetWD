document.getElementById('bid-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const maxBid = document.getElementById('max-bid').value;
    const currentBid = document.getElementById('current-bid').textContent.replace('€', '');
    if (parseFloat(maxBid) > parseFloat(currentBid)) {
        document.getElementById('current-bid').textContent = maxBid + '€';
        document.getElementById('bid-message').textContent = 'Votre enchère de ' + maxBid + '€ a été enregistrée.';
    } else {
        document.getElementById('bid-message').textContent = 'Votre enchère doit être supérieure à l\'enchère actuelle.';
    }
});

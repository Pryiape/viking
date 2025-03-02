document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-register');

    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        const username = document.getElementById('inputUsername').value;
        const email = document.getElementById('inputEmail4').value;
        const password = document.getElementById('inputPassword4').value;
        const passwordConfirmation = document.getElementById('inputPasswordConfirmation').value;
        const terms = document.getElementById('terms').checked;

        // Regex patterns
        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/; // Alphanumeric and underscores, 3-20 characters
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email format

        // Validate fields
        if (!username || !email || !password || !passwordConfirmation) {
            alert('Tous les champs sont requis.');
            return;
        }

        if (!usernameRegex.test(username)) {
            alert('Le format du champ nom d\'utilisateur est invalide.');
            return;
        }

        if (!emailRegex.test(email)) {
            alert('Veuillez entrer une adresse email valide.');
            return;
        }

        if (password !== passwordConfirmation) {
            alert('Les mots de passe ne correspondent pas.');
            return;
        }

        if (!terms) {
            alert('Vous devez accepter les termes d\'utilisation.');
            return;
        }

        // Check if email exists
        const url = document.getElementById('inputEmail4').dataset.urlExistEmail;
        const token = document.getElementById('inputEmail4').dataset.token;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                alert('L\'email a déjà été pris.');
            } else {
                form.submit(); // Submit the form if all validations pass
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    });
});

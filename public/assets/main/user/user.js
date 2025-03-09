document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-register');

    form.addEventListener('submit', async function (event) {
        // Ensure the form is submitted only through this handler
        event.preventDefault(); // Prevent immediate submission
        console.log('Form submission triggered'); // Log form submission
    
        const username = document.getElementById('inputUsername').value;
        const email = document.getElementById('inputEmail4').value;
        const password = document.getElementById('inputPassword4').value;
        const passwordConfirmation = document.getElementById('inputPasswordConfirmation').value;
        const terms = document.getElementById('terms').checked;
    
        // Regex patterns
        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/; 
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
    
        // Field validation
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
    
        try {
            const url = document.getElementById('inputEmail4').dataset.urlExistEmail;
            const token = document.getElementById('inputEmail4').dataset.token;
    
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ email: email })
            });
    
            const data = await response.json();
    
            if (data.exists) {
                alert('L\'email a déjà été pris.');
            } else {
                console.log('Form action before submission:', form.action); // Log the form action
                console.log('Email is available, submitting the form...');
                // Ensure the form is submitted through the JavaScript handler
                form.submit(); // Let Laravel handle the submission
                console.log('Form submitted successfully'); // Log successful submission
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});

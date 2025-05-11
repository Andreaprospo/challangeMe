<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registrazione</title>
        <link rel="icon" type="image/png" href="icona.png">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="CSS/styleRegistrati.css">
    </head>
    <body>
        <div class="container d-flex justify-content-center align-items-center min-vh-100">
            <div class="registration-container">
                <div class="registration-header">
                    <h2><i class="fas fa-user-plus me-2"></i>Registrazione</h2>
                    <p class="text-muted">Crea un nuovo account</p>
                </div>
                
                <form id="registrationForm">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        <label for="username"><i class="fas fa-user me-2"></i>Username</label>
                        <div class="invalid-feedback">
                            Inserisci un username valido.
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="mail" name="mail" placeholder="Email" required>
                        <label for="mail"><i class="fas fa-envelope me-2"></i>Email</label>
                        <div class="invalid-feedback">
                            Inserisci un indirizzo email valido.
                        </div>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        <div class="invalid-feedback">
                            Inserisci una password.
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-primary registration-btn">
                        <i class="fas fa-user-plus me-2"></i>Registrati
                    </button>
                </form>
                
                <div class="login-link">
                    <p>Hai già un account? <a href="login.php" class="text-decoration-none">Accedi qui</a></p>
                </div>
            </div>
        </div>
        
        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Form validation setup
                
                // Form validation
                const form = document.getElementById('registrationForm');
                const inputs = form.querySelectorAll('input[required]');
                
                function validateInput(input) {
                    if (!input.value) {
                        input.classList.add('is-invalid');
                        return false;
                    } else if (input.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
                        input.classList.add('is-invalid');
                        return false;
                    } else {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                        return true;
                    }
                }
                
                inputs.forEach(input => {
                    input.addEventListener('blur', () => validateInput(input));
                    input.addEventListener('input', () => {
                        if (input.classList.contains('is-invalid')) {
                            validateInput(input);
                        }
                    });
                });
                
                // Registration button click handler
                document.querySelector(".registration-btn").addEventListener("click", async function() {
                    let isValid = true;
                    
                    // Validate all inputs
                    inputs.forEach(input => {
                        if (!validateInput(input)) {
                            isValid = false;
                        }
                    });
                    
                    if (!isValid) {
                        // Shake animation for invalid form
                        const container = document.querySelector('.registration-container');
                        container.classList.add('shake');
                        setTimeout(() => container.classList.remove('shake'), 600);
                        return;
                    }
                    
                    // Call registration function
                    await checkRegistrati();
                });
            });

            async function checkRegistrati()
            {
                const registrationBtn = document.querySelector('.registration-btn');
                const registrationContainer = document.querySelector('.registration-container');
                
                // Show loading state
                registrationBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Registrazione in corso...';
                registrationBtn.disabled = true;
                
                try {
                    let url = "ajax/registrati.php?username=" + 
                              encodeURIComponent(document.getElementsByName("username")[0].value) + 
                              "&mail=" + encodeURIComponent(document.getElementsByName("mail")[0].value) + 
                              "&password=" + encodeURIComponent(document.getElementsByName("password")[0].value);
                    
                    console.log(url);
                    let response = await fetch(url);
                    let txt = await response.text();    //NON USARE JSON
                    console.log(txt);
                    let json = JSON.parse(txt);
                    console.log(json);
                    
                    if(json["status"] == "ERR") {
                        // Reset button
                        registrationBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Registrati';
                        registrationBtn.disabled = false;
                        
                        // Show error with animation
                        registrationContainer.classList.add('shake');
                        setTimeout(() => registrationContainer.classList.remove('shake'), 600);
                        
                        // Create error alert
                        const errorAlert = document.createElement('div');
                        errorAlert.className = 'alert alert-danger mt-3 mb-0';
                        errorAlert.role = 'alert';
                        errorAlert.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>${json["msg"]}`;
                        
                        // Remove any existing alerts
                        const existingAlert = document.querySelector('.alert');
                        if (existingAlert) {
                            existingAlert.remove();
                        }
                        
                        // Add new alert
                        document.getElementById('registrationForm').after(errorAlert);
                        
                        // Hide alert after 5 seconds
                        setTimeout(() => {
                            errorAlert.classList.add('fade');
                            setTimeout(() => errorAlert.remove(), 500);
                        }, 5000);
                        
                        return null;
                    }
                    
                    // Success state
                    registrationBtn.innerHTML = '<i class="fas fa-check me-2"></i>Registrazione completata!';
                    registrationBtn.classList.remove('btn-primary');
                    registrationBtn.classList.add('btn-success');
                    
                    // Success alert
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success mt-3 mb-0';
                    successAlert.role = 'alert';
                    successAlert.innerHTML = '<i class="fas fa-check-circle me-2"></i>Registrazione avvenuta con successo! Verrai reindirizzato alla pagina di login.';
                    
                    // Remove any existing alerts
                    const existingAlert = document.querySelector('.alert');
                    if (existingAlert) {
                        existingAlert.remove();
                    }
                    
                    // Add success alert
                    document.getElementById('registrationForm').after(successAlert);
                    
                    setTimeout(() => {
                        location.href = "login.php";
                    }, 2000);
                    
                } catch (error) {
                    console.error("Error:", error);
                    
                    // Reset button
                    registrationBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Registrati';
                    registrationBtn.disabled = false;
                    
                    // Show error alert
                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'alert alert-danger mt-3';
                    errorAlert.role = 'alert';
                    errorAlert.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>Si è verificato un errore: ${error.message}`;
                    
                    // Remove any existing alerts
                    const existingAlert = document.querySelector('.alert');
                    if (existingAlert) {
                        existingAlert.remove();
                    }
                    
                    // Add new alert
                    document.getElementById('registrationForm').after(errorAlert);
                }
            }
        </script>
    </body>
</html>
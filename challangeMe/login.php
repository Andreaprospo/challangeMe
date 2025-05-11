<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="icon" type="image/png" href="icona.png">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/styleLogin.css ">
    </head>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
            <div class="login-container">
                <div class="login-header">
                    <h2><i class="fas fa-user-circle me-2"></i>Login</h2>
                    <p class="text-muted">Welcome back! Please login to your account</p>
                </div>
                
                <form id="loginForm">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="identificativo" name="identificativo" placeholder="Username or Email" required>
                        <label for="identificativo"><i class="fas fa-user me-2"></i>Username or Email</label>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        <div class="password-toggle position-absolute top-50 end-0 translate-middle-y me-3" style="cursor: pointer;">
                            <i class="far fa-eye-slash" id="togglePassword"></i>
                        </div>
                    </div>
                
                    <button type="button" class="btn btn-primary login-btn"><i class="fas fa-sign-in-alt me-2"></i>Login</button>
                </form>
                
                <div class="register-link">
                    <p>Don't have an account? <a href="registrati.php" class="text-decoration-none">Register here</a></p>
                </div>
            </div>
        </div>
        
        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Password visibility toggle
                const togglePassword = document.querySelector('#togglePassword');
                const password = document.querySelector('#password');
                
                togglePassword.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
                
                // Login button click handler
                document.querySelector(".login-btn").addEventListener("click", checkLogin);
            });

            async function checkLogin() {
                const loginForm = document.getElementById('loginForm');
                const loginBtn = document.querySelector('.login-btn');
                const loginContainer = document.querySelector('.login-container');
                
                // Show loading state
                loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Logging in...';
                loginBtn.disabled = true;
                
                try {
                    let url = "ajax/checkLogin.php?identificativo=" + 
                              encodeURIComponent(document.getElementsByName("identificativo")[0].value) + 
                              "&password=" + encodeURIComponent(document.getElementsByName("password")[0].value);
                    
                    let response = await fetch(url);
                    if(!response.ok) {
                        throw new Error("HTTP Error: " + response.status);
                    }
                    
                    let txt = await response.text();    // NON USARE JSON
                    console.log(txt);
                    let data = JSON.parse(txt);
                    console.log(data);

                    if(data["status"] == "ERR") {
                        // Reset button
                        loginBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Login';
                        loginBtn.disabled = false;
                        
                        // Show error with animation
                        loginContainer.classList.add('shake');
                        setTimeout(() => loginContainer.classList.remove('shake'), 600);
                        
                        // Create error alert
                        const errorAlert = document.createElement('div');
                        errorAlert.className = 'alert alert-danger mt-3 mb-0';
                        errorAlert.role = 'alert';
                        errorAlert.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>${data["msg"]}`;
                        
                        // Remove any existing alerts
                        const existingAlert = document.querySelector('.alert');
                        if (existingAlert) {
                            existingAlert.remove();
                        }
                        
                        // Add new alert
                        loginForm.after(errorAlert);
                        
                        // Hide alert after 5 seconds
                        setTimeout(() => {
                            errorAlert.classList.add('fade');
                            setTimeout(() => errorAlert.remove(), 500);
                        }, 5000);
                        
                        return null;
                    }
                    
                    // Success state
                    loginBtn.innerHTML = '<i class="fas fa-check me-2"></i>Success!';
                    loginBtn.classList.remove('btn-primary');
                    loginBtn.classList.add('btn-success');
                    
                    setTimeout(() => {
                        location.href = "home.php";
                    }, 1000);
                    
                } catch (error) {
                    console.error("Error:", error);
                    
                    // Reset button
                    loginBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Login';
                    loginBtn.disabled = false;
                    
                    // Show error alert
                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'alert alert-danger mt-3';
                    errorAlert.role = 'alert';
                    errorAlert.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>An error occurred: ${error.message}`;
                    
                    // Remove any existing alerts
                    const existingAlert = document.querySelector('.alert');
                    if (existingAlert) {
                        existingAlert.remove();
                    }
                    
                    // Add new alert
                    loginForm.after(errorAlert);
                }
            }
        </script>
    </body>
</html>
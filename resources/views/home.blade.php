<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            gap: 30px;
            padding: 0 20px;
        }

        #auth-container > div {
            width: 100%;
            max-width: 400px; 
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            #auth-container {
                flex-direction: column;
                gap: 20px;
            }

            #auth-container > div {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div id="auth-container">
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const token = localStorage.getItem('token');
        const authContainer = document.getElementById('auth-container');
    
        if (token) {
            authContainer.innerHTML = `
                <p>You are logged in.</p>
                <button id="logout-btn">Log Out</button>
            `;
    
            document.getElementById('logout-btn').addEventListener('click', async () => {
                try {
                    const response = await fetch('/logins/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
    
                    if (response.ok) {
                        localStorage.removeItem('token');
                        window.location.href = '/';
                        Swal.fire({
                            icon: 'success',
                            title: 'Logged out successfully!',
                            timer: 3000, 
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to log out.',
                            text: 'Please try again later.',
                            timer: 3000,  
                        });
                    }
                } catch (error) {
                    console.error('Logout error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'An error occurred while trying to log out.',
                        text: 'Please try again later.',
                    });
                }
            });
        } else {
            authContainer.innerHTML = `
                <div>
                    <h1>Register</h1>
                    <form id="register-form">
                        <input name="name" type="text" placeholder="Name" required>
                        <input name="email" type="email" placeholder="Email" required>
                        <input name="password" type="password" placeholder="Password" required>
                        <button type="submit">Register</button>
                    </form>
                </div>
                <div>
                    <h1>Login</h1>
                    <form id="login-form">
                        <input name="loginname" type="text" placeholder="Name" required>
                        <input name="loginpassword" type="password" placeholder="Password" required>
                        <button type="submit">Login</button>
                    </form>
                </div>
            `;
    
            document.getElementById('login-form').addEventListener('submit', async (event) => {
                event.preventDefault();
                const formData = new FormData(event.target);
    
                try {
                    const response = await fetch('/logins/login-token', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
    
                    if (response.ok) {
                        const data = await response.json();
                        localStorage.setItem('token', data.token);
                        window.location.href = '/items'; 
                        Swal.fire({
                            icon: 'success',
                            title: 'Logged in successfully!',
                        });
                    } else {
                        const data = await response.json();
                        Swal.fire({
                            icon: 'error',
                            title: 'Login failed!',
                            text: data.message || 'Please check your credentials and try again.',
                        });
                    }
                } catch (error) {
                    console.error('Login error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'An error occurred during login.',
                        text: 'Please try again later.',
                    });
                }
            });
    
            document.getElementById('register-form').addEventListener('submit', async (event) => {
                event.preventDefault();
                const formData = new FormData(event.target);
    
                try {
                    const response = await fetch('/logins/register', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
    
                    if (response.ok) {
                        const data = await response.json();
                        localStorage.setItem('token', data.token);
                        window.location.href = '/items'; 
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration successful!',
                        });
                    } else {
                        const data = await response.json();
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration failed!',
                            text: data.message || 'Please check your details and try again.',
                        });
                    }
                } catch (error) {
                    console.error('Registration error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'An error occurred during registration.',
                        text: 'Please try again later.',
                    });
                }
            });
        }
    </script>
    
</body>
</html>

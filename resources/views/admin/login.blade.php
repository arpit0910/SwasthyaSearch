<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Arogio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0F172B;
            /* Premium Dark Navy */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 420px;
            padding: 3rem 2.5rem;
        }

        .brand-logo {
            font-size: 2rem;
            font-weight: 700;
            color: #0F172B;
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo span {
            color: #14b8a6;
        }

        .btn-primary {
            background-color: #14b8a6;
            border-color: #14b8a6;
            padding: 0.75rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #0d9488;
            border-color: #0d9488;
        }

        .form-control:focus {
            border-color: #14b8a6;
            box-shadow: 0 0 0 0.25rem rgba(20, 184, 166, 0.25);
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="brand-logo">
            Swasthya<span>Search</span>
            <div class="fs-6 fw-normal text-muted mt-1">Secure Administration Portal</div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 fs-6 shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-semibold text-secondary">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i
                            class="fa-solid fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" value="{{ old('email') }}"
                        required autofocus placeholder="admin@xyz.com">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-secondary">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i
                            class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" required
                        placeholder="••••••••">
                </div>
            </div>

            <div class="mb-4 d-flex align-items-center justify-content-between">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label text-muted fs-6" for="remember">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In to Dashboard
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Access Denied', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    *, *::before, *::after { 
      box-sizing: border-box; 
      margin: 0; 
      padding: 0; 
    }

    :root {
      --earth:    #2B2A25;
      --leaf:     #3D6B3F;
      --leaf-lt:  #4F8A52;
      --crop:     #C8A84B;
      --crop-lt:  #E8C96A;
      --cream:    #F5F0E8;
      --cream-dk: #EBE4D6;
      --mist:     #F9F6F0;
      --charcoal: #1A1A16;
      --text-muted: #6B6558;
      --border:   rgba(43,42,37,0.12);
      --font-head: 'DM Serif Display', Georgia, serif;
      --font-body: 'DM Sans', sans-serif;
    }

    body {
      font-family: var(--font-body);
      background: linear-gradient(135deg, var(--mist) 0%, var(--cream) 100%);
      color: var(--earth);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .access-denied-container {
      max-width: 500px;
      text-align: center;
      background: white;
      border-radius: 20px;
      padding: 3rem;
      box-shadow: 0 20px 60px rgba(43,42,37,0.1);
      border: 1px solid var(--border);
    }

    .error-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #dc3545, #c82333);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 2.5rem;
      margin: 0 auto 2rem;
    }

    .access-denied-container h1 {
      font-family: var(--font-head);
      font-size: 2.5rem;
      color: var(--earth);
      margin-bottom: 1rem;
    }

    .access-denied-container p {
      font-size: 1.1rem;
      color: var(--text-muted);
      line-height: 1.6;
      margin-bottom: 2rem;
    }

    .action-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease;
      cursor: pointer;
      border: none;
    }

    .btn-primary {
      background: var(--leaf);
      color: white;
    }

    .btn-primary:hover {
      background: var(--leaf-lt);
      transform: translateY(-2px);
    }

    .btn-secondary {
      background: transparent;
      color: var(--earth);
      border: 1px solid var(--border);
    }

    .btn-secondary:hover {
      background: var(--cream-dk);
    }

    @media (max-width: 768px) {
      .access-denied-container {
        padding: 2.5rem 2rem;
      }
      
      .error-icon {
        width: 80px;
        height: 80px;
        font-size: 2rem;
      }
    }

    @media (max-width: 640px) {
      .access-denied-container {
        padding: 2rem 1.5rem;
      }
      
      .access-denied-container h1 {
        font-size: 1.75rem;
      }
      
      .access-denied-container p {
        font-size: 1rem;
      }
    }

    /* Critical Breakpoint - Fix 531px width issue */
    @media (max-width: 540px) {
      .access-denied-container {
        padding: 1.75rem 1.25rem;
      }
      
      .error-icon {
        width: 70px;
        height: 70px;
        font-size: 1.875rem;
      }
      
      .access-denied-container h1 {
        font-size: 1.625rem;
      }
      
      .access-denied-container p {
        font-size: 0.95rem;
      }
      
      .action-buttons {
        gap: 0.875rem;
      }
      
      .btn {
        padding: 11px 19px;
        font-size: 0.925rem;
      }
    }

    @media (max-width: 480px) {
      .access-denied-container {
        padding: 1.5rem 1rem;
      }
      
      .error-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
      }
      
      .access-denied-container h1 {
        font-size: 1.5rem;
      }
      
      .access-denied-container p {
        font-size: 0.95rem;
      }
      
      .action-buttons {
        flex-direction: column;
        gap: 0.75rem;
      }
      
      .btn {
        width: 100%;
        justify-content: center;
        padding: 12px 20px;
      }
    }

    @media (max-width: 360px) {
      .access-denied-container {
        padding: 1rem 0.75rem;
      }
      
      .access-denied-container h1 {
        font-size: 1.25rem;
      }
    }
  </style>
</head>
<body>
  <div class="access-denied-container">
    <div class="error-icon">
      <i class="fa fa-lock"></i>
    </div>
    <h1>Access Denied</h1>
    <p>You don't have permission to access this area. Please contact an administrator if you believe this is an error.</p>
    
    <div class="action-buttons">
      <a href="/dashboard" class="btn btn-primary">
        <i class="fa fa-home"></i>
        Go to Dashboard
      </a>
      <a href="/logout" class="btn btn-secondary">
        <i class="fa fa-sign-out"></i>
        Logout
      </a>
    </div>
  </div>
</body>
</html>

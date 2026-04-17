<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500&display=swap" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="auth-mont">
  <?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

  <div class="tilt-container">
    <div class="tilt-card" id="tilt-card">
      <div class="glare" id="glare"></div>
      <form method="post" action="<?= $base ?>/login">
    <div class="container">
      <h1>Sign In</h1>
      <p>Please sign in to your account.</p>

      <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>" />

      <label for="email"><b>Email</b></label>
      <input id="email" type="text" placeholder="Enter Email" name="email" value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
      <?php if (!empty($errors['email'])): ?><div class="err"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

      <label for="password"><b>Password</b></label>
      <div class="input-group" id="show_hide_password">
        <input id="password" class="form-control" type="password" placeholder="Enter Password" name="password" required>
        <div class="input-group-addon">
          <a href="#" id="password-toggle-link" style="text-decoration: none; color: #666;">
            <i class="fa fa-eye-slash" id="password-icon" aria-hidden="true"></i>
          </a>
        </div>
      </div>
      <?php if (!empty($errors['password'])): ?><div class="err"><?= htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

      <div class="clearfix">
        <button type="submit" class="btn">Sign In</button>
      </div>

      <p>New to Ulimi? <a href="<?= $base ?>/register">Sign up now</a></p>
    </div>
  </form>
    </div>
  </div>
</body>
</html>

<style>
  /* 3D Tilt Effect Styles */
  .tilt-container {
    perspective: 1000px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
  }
  
  .tilt-card {
    position: relative;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.1s ease-out, box-shadow 0.3s;
    transform-style: preserve-3d;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  .glare {
    position: absolute;
    inset: 0;
    border-radius: inherit;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s;
  }
  
  /* Password Toggle Styles */
  .input-group {
    display: flex;
    position: relative;
    width: 100%;
  }
  
  .form-control {
    flex: 1;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    margin: 5px 0 22px 0;
    display: inline-block;
    box-sizing: border-box;
    padding-right: 45px;
  }
  
  .form-control:focus {
    outline: none;
    border: 1px solid #4a7c4e;
    box-shadow: 0 0 5px rgba(74, 124, 78, 0.3);
  }
  
  .input-group-addon {
    position: absolute;
    right: 1px;
    top: 6px;
    bottom: 22px;
    display: flex;
    align-items: center;
    padding: 0 12px;
    background: transparent;
    border: none;
    margin: 0;
  }
  
  .input-group .form-control {
    border-radius: 4px;
    margin-right: 0;
  }
  
  .input-group-addon a {
    outline: none;
    transition: color 0.2s;
    color: #666;
    text-decoration: none;
    font-size: 16px;
  }
  
  .input-group-addon a:hover {
    color: #4a7c4e !important;
  }
  
  .input-group-addon a:focus {
    color: #4a7c4e !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Password Toggle Functionality
    $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass("fa-eye-slash");
            $('#show_hide_password i').removeClass("fa-eye");
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass("fa-eye-slash");
            $('#show_hide_password i').addClass("fa-eye");
        }
    });
    
    // 3D Tilt Effect
    const tiltCard = document.getElementById('tilt-card');
    const glare = document.getElementById('glare');
    const maxRotation = 5; // Subtle rotation

    tiltCard.addEventListener('mousemove', (e) => {
        const rect = tiltCard.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const rotateY = -((x - centerX) / centerX) * maxRotation;
        const rotateX = -((centerY - y) / centerY) * maxRotation;
        
        tiltCard.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
        tiltCard.style.boxShadow = `${-rotateY * 2}px ${rotateX * 2}px 20px rgba(0,0,0,0.15)`;

        const glareX = (x / rect.width) * 100;
        const glareY = (y / rect.height) * 100;
        glare.style.opacity = '0.1';
        glare.style.background = `radial-gradient(circle at ${glareX}% ${glareY}%, rgba(255,255,255,0.6), transparent 60%)`;
    });

    tiltCard.addEventListener('mouseleave', () => {
        tiltCard.style.transform = 'rotateX(0deg) rotateY(0deg) scale3d(1,1,1)';
        tiltCard.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        glare.style.opacity = '0.4';
    });
  });
</script>

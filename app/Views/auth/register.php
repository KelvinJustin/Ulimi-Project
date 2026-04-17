<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500&display=swap" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="auth-mont">
  <?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

  <form method="post" action="<?= $base ?>/register">
    <div class="container">
      <h1>Sign Up</h1>
      <p>Please fill in this form to create an account.</p>

      <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>" />

      <label for="role"><b>I am a</b></label>
      <select name="role" id="role" required>
        <option value="buyer" <?= ($old['role'] ?? 'buyer') === 'buyer' ? 'selected' : '' ?>>Buyer</option>
        <option value="seller" <?= ($old['role'] ?? 'buyer') === 'seller' ? 'selected' : '' ?>>Seller</option>
      </select>
      <?php if (!empty($errors['role'])): ?><div class="err"><?= htmlspecialchars($errors['role'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

      <label for="display_name"><b>Username</b></label>
      <input id="display_name" type="text" name="display_name" placeholder="Enter Username" value="<?= htmlspecialchars($old['display_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
      <?php if (!empty($errors['display_name'])): ?><div class="err"><?= htmlspecialchars($errors['display_name'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

      <label for="email"><b>Email</b></label>
      <input id="email" type="text" placeholder="Enter Email" name="email" value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
      <?php if (!empty($errors['email'])): ?><div class="err"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

      <label for="password"><b>Password</b></label>
      <div class="input-group" id="show_hide_password_register">
        <input id="password" class="form-control" type="password" placeholder="Enter Password" name="password" required>
        <div class="input-group-addon">
          <a href="#" id="password-toggle-link-register" style="text-decoration: none; color: #666;">
            <i class="fa fa-eye-slash" id="password-icon-register" aria-hidden="true"></i>
          </a>
        </div>
      </div>
      <?php if (!empty($errors['password'])): ?><div class="err"><?= htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

      <label for="phone"><b>Phone Number</b></label>
      <br>
      <span class="phone-code-fixed" aria-hidden="true">+265</span>
      <input type="hidden" name="phoneCode" value="+265">
      <input id="phone" type="phone" name="phone" placeholder="812345678" value="<?= htmlspecialchars($old['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

      <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms &amp; Privacy</a>.</p>

      <div class="clearfix">
        <button type="submit" class="btn">Sign Up</button>
      </div>

      <p>Already have an account? <a href="<?= $base ?>/login">Log In</a></p>
    </div>
  </form>

<style>
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
    $("#show_hide_password_register a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password_register input').attr("type") == "text"){
            $('#show_hide_password_register input').attr('type', 'password');
            $('#show_hide_password_register i').addClass("fa-eye-slash");
            $('#show_hide_password_register i').removeClass("fa-eye");
        }else if($('#show_hide_password_register input').attr("type") == "password"){
            $('#show_hide_password_register input').attr('type', 'text');
            $('#show_hide_password_register i').removeClass("fa-eye-slash");
            $('#show_hide_password_register i').addClass("fa-eye");
        }
    });
  });
</script>
</body>
</html>

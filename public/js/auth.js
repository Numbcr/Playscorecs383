function validateAndRegister(e) {
  e.preventDefault();

  const name = $('#name').val();
  const email = $('#email').val();
  const password = $('#password').val();
  const confirm = $('#password_confirmation').val();
  const csrfToken = $('meta[name="csrf-token"]').attr('content');

  if (password !== confirm) {
    alert("Passwords do not match");
    return;
  }

  $.ajax({
    url: '/api/auth/register',
    method: 'POST',
    contentType: 'application/json',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    data: JSON.stringify({
      name,
      email,
      password,
      password_confirmation: confirm
    }),
    success: function(response) {
      if (response.success) {
        alert("Registered!");
        window.location.href = "/login";
      } else {
        alert(response.message);
      }
    },
    error: function(xhr) {
      let message = "Registration failed";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        message = xhr.responseJSON.message;
      }
      alert(message);
    }
  });
}

function login(e) {
  e.preventDefault();
  const email = $('#email').val();
  const password = $('#password').val();
  const csrfToken = $('meta[name="csrf-token"]').attr('content');

  $.ajax({
    url: '/api/auth/login',
    method: 'POST',
    contentType: 'application/json',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    data: JSON.stringify({
      email: email,
      password: password
    }),
    success: function(response) {
      if (response.success) {
        localStorage.setItem('user', JSON.stringify(response.user));
        setTimeout(function() {
          window.location.href = "/";
        }, 100);
      } else {
        alert(response.message || "Login failed.");
      }
    },
    error: function(xhr, status, error) {
      let message = "Login failed";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        message = xhr.responseJSON.message;
      }
      alert(message);
    }
  });
}

function checkAuth() {
  return $.ajax({
    url: '/api/session-check',
    method: 'GET'
  });
}

function updateNavigation() {
    const loginButton = document.querySelector('.login-button');
    if (!loginButton) {
        console.warn('Login button element not found');
        return;
    }

    $.ajax({
        url: '/api/session-check',
        method: 'GET',
        success: function(response) {
            console.log('Session check response:', response);
            if (response.loggedIn) {
                const adminLink = response.isAdmin ? 
                    '<li><a class="dropdown-item" href="/admin/dashboard">Admin Dashboard</a></li>' : '';
                loginButton.innerHTML = `
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> ${response.username}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            ${adminLink}
                            <li><a class="dropdown-item" href="#" onclick="logout()">Logout</a></li>
                        </ul>
                    </div>`;
                
                var dropdowns = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'))
                dropdowns.map(function (el) {
                    return new bootstrap.Dropdown(el);
                });
            } else {
                loginButton.innerHTML = '<a href="/login" class="btn btn-outline-light">Login</a>';
            }
        },
        error: function(xhr) {
            console.error('Session check failed:', xhr);
            const loginButton = document.querySelector('.login-button');
            if (loginButton) {
                loginButton.innerHTML = '<a href="/login" class="btn btn-outline-light">Login</a>';
            }
        }
    });
}

function logout() {
  const csrfToken = $('meta[name="csrf-token"]').attr('content');
  
  $.ajax({
    url: '/api/auth/logout',
    method: 'POST',
    contentType: 'application/json',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    success: function(response) {
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
  });
}

$(document).ready(function() {
  updateNavigation();
  
  // Periodically check for login status changes
  setInterval(updateNavigation, 5000);
});

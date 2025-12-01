function validateAndRegister(e) {
  e.preventDefault();

  const name = document.getElementById('name').value;
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const confirm = document.getElementById('password_confirmation').value;
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  if (password !== confirm) {
    alert("Passwords do not match");
    return;
  }

  fetch('/api/auth/register', {
    method: 'POST',
    contentType: 'application/json',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      name,
      email,
      password,
      password_confirmation: confirm
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Registered!");
      window.location.href = "/login";
    } else {
      alert(data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert("Registration failed");
  });
}

function login(e) {
  e.preventDefault();
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  fetch('/api/auth/login', {
    method: 'POST',
    contentType: 'application/json',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      email: email,
      password: password
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      localStorage.setItem('user', JSON.stringify(data.user));
      setTimeout(function() {
        window.location.href = "/";
      }, 100);
    } else {
      alert(data.message || "Login successed .");
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert("Login failed");
  });
}

function checkAuth() {
  return fetch('/api/session-check', {
    method: 'GET'
  });
}

function updateNavigation() {
    const loginButton = document.querySelector('.login-button');
    if (!loginButton) {
        console.warn('Login button element not found');
        return;
    }

    fetch('/api/session-check', {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        console.log('Session check response:', data);
        if (data.loggedIn) {
            const adminLink = data.isAdmin ? 
                `<li><a class="dropdown-item" href="/admin/dashboard">${window.translations?.admin_dashboard || 'Admin Dashboard'}</a></li>` : '';
            loginButton.innerHTML = `
                <div class="dropdown">
                    <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> ${data.username}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        ${adminLink}
                        <li><a class="dropdown-item" href="#" onclick="logout()">${window.translations?.logout || 'Logout'}</a></li>
                    </ul>
                </div>`;
            
            var dropdowns = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'))
            dropdowns.map(function (el) {
                return new bootstrap.Dropdown(el);
            });
        } else {
            loginButton.innerHTML = `<a href="/login" class="btn btn-outline-light">${window.translations?.login || 'Login'}</a>`;
        }
    })
    .catch(error => {
        console.error('Session check failed:', error);
        const loginButton = document.querySelector('.login-button');
        if (loginButton) {
            loginButton.innerHTML = `<a href="/login" class="btn btn-outline-light">${window.translations?.login || 'Login'}</a>`;
        }
    });
}

function logout() {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  
  fetch('/api/auth/logout', {
    method: 'POST',
    contentType: 'application/json',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    localStorage.removeItem('user');
    window.location.href = '/login';
  })
  .catch(error => {
    console.error('Logout error:', error);
    window.location.href = '/login';
  });
}

document.addEventListener('DOMContentLoaded', function() {
  updateNavigation();
  
  // Periodically check for login status changes
  setInterval(updateNavigation, 5000);
});

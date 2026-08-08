document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('registerForm');
  if (!form) return;

  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', () => {
      const input = document.getElementById(button.dataset.target);
      if (!input) return;
      input.type = input.type === 'password' ? 'text' : 'password';
    });
  });

  form.addEventListener('submit', (e) => {
    const values = {
      username: document.getElementById('username').value.trim(),
      email: document.getElementById('email').value.trim(),
      password: document.getElementById('password').value,
      confirm: document.getElementById('confirm_password').value,
      contact: document.getElementById('contact_number').value.trim(),
      dob: document.getElementById('dob').value,
      address: document.getElementById('address').value.trim(),
      postal: document.getElementById('postal_code').value.trim()
    };

    const errors = {};
    if (values.username.length < 3 || values.username.length > 21) errors.username = 'Username must be 3–21 characters.';
    if (!/^\S+@\S+\.\S+$/.test(values.email)) errors.email = 'Enter a valid email address.';
    if (!/^(?=.*\d)(?=.*[^A-Za-z0-9]).{8,32}$/.test(values.password)) errors.password = 'Use 8–32 characters with a number and special character.';
    if (values.password !== values.confirm) errors.confirm = 'Passwords do not match.';
    if (!/^\d{8}$/.test(values.contact)) errors.contact = 'Contact number must be exactly 8 digits.';
    if (!values.dob) errors.dob = 'Date of birth is required.';
    if (values.address && !/^\d+$/.test(values.postal)) errors.postal = 'Postal code must contain digits only.';

    ['username','email','password','confirm','contact','dob','postal'].forEach(k => {
      const el = document.getElementById('err-' + k);
      if (el) el.textContent = errors[k] || '';
    });

    if (Object.keys(errors).length) e.preventDefault();
  });
});

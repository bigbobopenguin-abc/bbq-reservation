document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('contactForm');
  if (!form) return;
  form.addEventListener('submit', (e) => {
    const fields = {
      name: document.getElementById('name'),
      email: document.getElementById('email'),
      subject: document.getElementById('subject'),
      message: document.getElementById('message')
    };
    const errors = {
      name: fields.name.value.trim() ? '' : 'Name is required.',
      email: /^\S+@\S+\.\S+$/.test(fields.email.value.trim()) ? '' : 'Enter a valid email address.',
      subject: fields.subject.value ? '' : 'Choose a subject.',
      message: fields.message.value.trim() ? '' : 'Message is required.'
    };
    Object.keys(errors).forEach(k => {
      const el = document.getElementById('err-' + k);
      if (el) el.textContent = errors[k];
    });
    if (Object.values(errors).some(Boolean)) e.preventDefault();
  });
});

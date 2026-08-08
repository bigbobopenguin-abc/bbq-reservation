document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('reservationForm');
  if (!form) return;
  const date = document.getElementById('booking_date');
  const start = document.getElementById('start_time');
  const end = document.getElementById('end_time');
  const today = new Date();
  const local = new Date(today.getTime() - today.getTimezoneOffset() * 60000).toISOString().slice(0,10);
  date.min = local;

  form.addEventListener('submit', (e) => {
    let ok = true;
    const dateErr = document.getElementById('err-date');
    const timeErr = document.getElementById('err-time');
    dateErr.textContent = '';
    timeErr.textContent = '';
    if (!date.value || date.value < local) { dateErr.textContent = 'Choose today or a future date.'; ok = false; }
    if (!start.value || !end.value || end.value <= start.value) { timeErr.textContent = 'End time must be after start time.'; ok = false; }
    if (!ok) e.preventDefault();
  });
});

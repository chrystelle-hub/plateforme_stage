function showAlert(id, msg, className) {
  // Get element
  const container = document.querySelector(id);
  container.className = className;
  container.innerHTML = msg;

  // Timeout after 3s
  setTimeout(() => {
    clearAlert(id);
  }, 3000);
}
function clearAlert(id) {
  const currentAlert = document.querySelector(id);

  if (currentAlert.innerHTML) {
    currentAlert.className = '';
    currentAlert.innerHTML = '';
  }
}

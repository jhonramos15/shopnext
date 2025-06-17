  document.getElementById("form-password").addEventListener("submit", async function(e) {
    e.preventDefault();
    
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    const nuevaClave = document.getElementById("clave").value;

    const res = await fetch('../php/update-password.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `token=${encodeURIComponent(token)}&clave=${encodeURIComponent(nuevaClave)}`
    });

    const msg = await res.text();
    alert(msg);
  });
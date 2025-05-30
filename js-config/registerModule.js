const handleRegister = async (event) => {
  event.preventDefault();

  const form = event.target;

  const formData = {
    username: form.username.value.trim(),
    email: form.email.value.trim(),
    password: form.password.value,
    bloodgroup: form.bloodgroup.value,
    contact: form.contact.value.trim(),
    location: form.location.value.trim(),
  };

  const errorMsg = document.getElementById("error-msg");
  errorMsg.textContent = "";
  errorMsg.classList.add("d-none");

  // ✅ Password validation
  if (formData.password.length < 5) {
    errorMsg.textContent = "Password must be at least 5 characters long.";
    errorMsg.classList.remove("d-none");
    return;
  }

  try {
    const res = await fetch("../api/register.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(formData),
    });

    const result = await res.json();

    if (!res.ok) throw new Error(result.error || "Registration failed");

    alert("Registration successful! Please log in.");
    window.location.href = "/login.html";
  } catch (err) {
    errorMsg.textContent = err.message;
    errorMsg.classList.remove("d-none");
  }
};
